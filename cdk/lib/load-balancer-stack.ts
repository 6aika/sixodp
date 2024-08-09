import {aws_elasticloadbalancingv2, aws_route53, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";
import {NetworkStackProps} from "./network-stack-props";
import {ApplicationProtocol} from "aws-cdk-lib/aws-elasticloadbalancingv2";
import {LoadBalancerTarget} from "aws-cdk-lib/aws-route53-targets";
import {AutoScalingGroup} from "aws-cdk-lib/aws-autoscaling";

export class LoadBalancerStack extends Stack {
    constructor(scope: Construct, id: string, props: NetworkStackProps) {
        super(scope, id, props);

        const zone = aws_route53.HostedZone.fromLookup(this, 'HostedZone', {
            domainName: props.fqdn
        })

        const loadbalancer = new aws_elasticloadbalancingv2.ApplicationLoadBalancer(this, 'loadBalancer', {
            vpc: props.vpc,
            internetFacing: true
        })
        /*
        const listener = loadbalancer.addListener('sslListerner', {
            port: 443,
            open: true
        })

        loadbalancer.addRedirect({
            sourceProtocol: ApplicationProtocol.HTTP,
            targetProtocol: ApplicationProtocol.HTTPS
        })

        
        const asg = new AutoScalingGroup(this, 'ScalingGroup', {
            vpc: props.vpc,
            vpcSubnets: {
                subnets: props.vpc.privateSubnets
            }
        })


        listener.addTargets('NginxMachines', {
            port: 80,
            targets: [asg]
        })
*/

        new aws_route53.ARecord(this, 'ARecord', {
            zone: zone,
            target: aws_route53.RecordTarget.fromAlias(new LoadBalancerTarget(loadbalancer)),
            recordName: 'dev'
        })

    }
}