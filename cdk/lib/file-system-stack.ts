import {aws_ec2, aws_efs, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";
import {NetworkStackProps} from "./network-stack-props";

export class FileSystemStack extends Stack {
    readonly fileSystem: aws_efs.FileSystem
    readonly migrationFileSystem: aws_efs.IFileSystem
    readonly migrationFsSecGroup: aws_ec2.ISecurityGroup

    constructor(scope: Construct, id: string, props: NetworkStackProps) {
        super(scope, id, props);

        this.fileSystem = new aws_efs.FileSystem(this, 'fileSystem', {
            vpc: props.vpc,
            performanceMode: aws_efs.PerformanceMode.GENERAL_PURPOSE,
            throughputMode: aws_efs.ThroughputMode.BURSTING,
            vpcSubnets: {
                subnets: props.vpc.privateSubnets
            },
            encrypted: true,
        })
    }
}