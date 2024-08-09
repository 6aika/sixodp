import {aws_ec2, aws_rds, Duration, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";

import {DatabaseStackProps} from "./database-stack-props";
import {Key} from "aws-cdk-lib/aws-kms";
import {SnapshotCredentials} from "aws-cdk-lib/aws-rds";
import {ISecurityGroup, SecurityGroup} from "aws-cdk-lib/aws-ec2";


export class DatabaseStack extends Stack {

    readonly ckanDatabase: aws_rds.IDatabaseInstance
    readonly ckanDatabaseSecurityGroup: ISecurityGroup
    constructor(scope: Construct, id: string, props: DatabaseStackProps) {
        super(scope, id, props);

        const secretEncryptionKey = Key.fromLookup(this, 'SecretEncryptionKey', {
            aliasName: 'alias/secret-encryption-key'
        })

        const ckanDatabaseMasterSecret = new aws_rds.DatabaseSecret(this, 'ckanMasterCredentials', {
            username: 'ckan_admin',
            encryptionKey: secretEncryptionKey
        })


        this.ckanDatabaseSecurityGroup = new SecurityGroup(this, 'ckanDatabaseSecGroup', {
            vpc: props.vpc
        })

        this.ckanDatabase = new aws_rds.DatabaseInstanceFromSnapshot(this, 'ckanDatabase', {
            vpc: props.vpc,
            snapshotIdentifier: props.ckanDatabaseSnapshot.stringValue,
            engine: aws_rds.DatabaseInstanceEngine.postgres({version: aws_rds.PostgresEngineVersion.VER_12}),
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T4G, aws_ec2.InstanceSize.SMALL),
            allocatedStorage: 50,
            maxAllocatedStorage: 100,
            backupRetention: Duration.days(7),
            vpcSubnets: {
                subnets: props.vpc.privateSubnets
            },
            credentials: SnapshotCredentials.fromSecret(ckanDatabaseMasterSecret),
            securityGroups: [
                this.ckanDatabaseSecurityGroup
            ]
        })

    }
}