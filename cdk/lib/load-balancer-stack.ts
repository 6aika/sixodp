import {aws_certificatemanager, aws_elasticloadbalancingv2, aws_route53, Duration, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";
import {ApplicationProtocol} from "aws-cdk-lib/aws-elasticloadbalancingv2";
import {LoadBalancerTarget} from "aws-cdk-lib/aws-route53-targets";
import {CertificateValidation} from "aws-cdk-lib/aws-certificatemanager";
import {LoadBalancerStackProps} from "./load-balancer-stack-props";


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

    }
}