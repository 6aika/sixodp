import {aws_ec2, Stack, StackProps} from "aws-cdk-lib";
import {Construct} from "constructs";
import {IVpc, NatProvider, Vpc} from "aws-cdk-lib/aws-ec2";

export class VpcStack extends Stack {

    readonly vpc: IVpc

    constructor(scope: Construct, id: string, props?: StackProps) {
        super(scope, id, props);

        //const natProvider = NatProvider.instanceV2({
        //    instanceType: new aws_ec2.InstanceType('t4g.nano'),
        //    defaultAllowedTraffic: aws_ec2.NatTrafficDirection.OUTBOUND_ONLY
        //})




        this.vpc = new Vpc(this, 'Vpc', {
            maxAzs: 2,
            //natGatewayProvider: natProvider,
            natGateways: 1,
            ipAddresses: aws_ec2.IpAddresses.cidr('10.0.0.0/16'),
            subnetConfiguration: [
                {
                    cidrMask: 24,
                    name: 'Public',
                    subnetType: aws_ec2.SubnetType.PUBLIC
                },
                {
                    cidrMask: 24,
                    name: 'VirtualMachine',
                    subnetType: aws_ec2.SubnetType.PRIVATE_WITH_EGRESS
                },
                {
                    cidrMask: 24,
                    name: 'Database',
                    subnetType: aws_ec2.SubnetType.PRIVATE_ISOLATED
                }

            ]

        })




    }
}