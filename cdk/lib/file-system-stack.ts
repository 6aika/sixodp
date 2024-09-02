import {aws_ec2, aws_efs, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";
import {FileSystemStackProps} from "./file-system-stack-props";

export class FileSystemStack extends Stack {
    readonly fileSystem: aws_efs.FileSystem
    readonly migrationFileSystem: aws_efs.IFileSystem
    readonly migrationFsSecGroup: aws_ec2.ISecurityGroup

    constructor(scope: Construct, id: string, props: FileSystemStackProps) {
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

        if (props.migrationFileSystemId && props.migrationFileSystemSecurityGroupId) {

            this.migrationFsSecGroup = aws_ec2.SecurityGroup.fromSecurityGroupId(this, 'secGroupId', props.migrationFileSystemSecurityGroupId, {
                allowAllOutbound: true
            })

            this.migrationFileSystem = aws_efs.FileSystem.fromFileSystemAttributes(this, 'migrationFs', {
                fileSystemId: props.migrationFileSystemId,
                securityGroup: this.migrationFsSecGroup
            })
        }
    }
}