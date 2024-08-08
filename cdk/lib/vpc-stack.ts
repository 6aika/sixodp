import {aws_ec2, Stack, StackProps} from "aws-cdk-lib";
import {Construct} from "constructs";
import {IVpc, NatProvider, Vpc} from "aws-cdk-lib/aws-ec2";

export class VpcStack extends Stack {

    readonly vpc: IVpc

    constructor(scope: Construct, id: string, props?: StackProps) {
        super(scope, id, props);

        const natProvider = NatProvider.instanceV2({
            instanceType: new aws_ec2.InstanceType('t4g.nano'),
            defaultAllowedTraffic: aws_ec2.NatTrafficDirection.OUTBOUND_ONLY
        })

        this.vpc = new Vpc(this, 'Vpc', {
            maxAzs: 1,
            natGatewayProvider: natProvider,
            ipAddresses: aws_ec2.IpAddresses.cidr('10.0.0.0/16')
        })

    }
}