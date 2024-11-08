import {
    aws_certificatemanager,
    aws_ec2,
    aws_elasticloadbalancingv2,
    aws_route53,
    aws_s3,
    Duration,
    Stack
} from "aws-cdk-lib";
import {Construct} from "constructs";
import {ApplicationProtocol} from "aws-cdk-lib/aws-elasticloadbalancingv2";
import {LoadBalancerTarget} from "aws-cdk-lib/aws-route53-targets";
import {CertificateValidation} from "aws-cdk-lib/aws-certificatemanager";
import {LoadBalancerStackProps} from "./load-balancer-stack-props";
import {BucketEncryption} from "aws-cdk-lib/aws-s3";
import {StringParameter} from "aws-cdk-lib/aws-ssm";


export class LoadBalancerStack extends Stack {

    constructor(scope: Construct, id: string, props: LoadBalancerStackProps) {
        super(scope, id, props);

        const zone = aws_route53.HostedZone.fromLookup(this, 'HostedZone', {
            domainName: props.fqdn
        })


        const certificate = new aws_certificatemanager.Certificate(this, 'certificate', {
            domainName: `${props.environment}.${props.fqdn}`,
            validation: CertificateValidation.fromDns(zone)
        })


        const loadBalancer = new aws_elasticloadbalancingv2.ApplicationLoadBalancer(this, 'loadBalancer', {
            vpc: props.vpc,
            internetFacing: true
        })


        loadBalancer.addRedirect({
            sourceProtocol: ApplicationProtocol.HTTP,
            targetProtocol: ApplicationProtocol.HTTPS
        })

        const listener = loadBalancer.addListener('sslListener', {
            port: 443,
            open: true,
            certificates: [
                certificate
            ]
        })


        listener.addTargets('NginxMachines', {
            port: 80,
            targets: [props.webServerAsg],
            healthCheck: {
                path: '/health'
            },
            stickinessCookieDuration: Duration.hours(1)
        })


        new aws_route53.ARecord(this, 'ARecord', {
            zone: zone,
            target: aws_route53.RecordTarget.fromAlias(new LoadBalancerTarget(loadBalancer)),
            recordName: props.environment
        })


        if ( props.pgAdminEnabled ) {

            const pgAdminCertificate = new aws_certificatemanager.Certificate(this, 'pgAdmincertificate', {
                domainName: `phppgadmin.${props.environment}.${props.fqdn}`,
                validation: CertificateValidation.fromDns(zone)
            })

            const pgAdminListener = loadBalancer.addListener('pgAdminListener', {
                port: 8000,
                open: false,
                protocol: ApplicationProtocol.HTTPS,
                certificates: [
                    pgAdminCertificate
                ]
            })

            pgAdminListener.addTargets('pgAdminMachines', {
                port: 8000,
                targets: [props.webServerAsg],
                healthCheck: {
                    path: '/health'
                },
                stickinessCookieDuration: Duration.hours(1)
            })

            new aws_route53.ARecord(this, 'pgAdminARecord', {
                zone: zone,
                target: aws_route53.RecordTarget.fromAlias(new LoadBalancerTarget(loadBalancer)),
                recordName: `phppgadmin.${props.environment}`
            })

            for (let i = 1; i <= props.numberOfAllowedIpsInPgAdmin; i++) {

                let allowedIpParameter = StringParameter.fromStringParameterName(this, `allowedIp${i}`,
                    `${props.pgAdminAllowedIpPrefix}${i}`)

                pgAdminListener.connections.allowFrom(aws_ec2.Peer.ipv4(allowedIpParameter.stringValue), aws_ec2.Port.tcp(8000))
            }
        }

        const logBucket = new aws_s3.Bucket(this, 'logBucket', {
            bucketName: `sixodp-${props.environment}-loadbalancer-logs`,
            blockPublicAccess: aws_s3.BlockPublicAccess.BLOCK_ALL,
            encryption: BucketEncryption.S3_MANAGED,
            versioned: true,
            lifecycleRules: [
                {
                    enabled: true,
                    expiration: Duration.days(30)
                }
            ]
        })

        loadBalancer.logAccessLogs(logBucket, this.stackName)
    }
}