import {aws_ec2, aws_rds, aws_secretsmanager, aws_ssm, Duration, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";

import {DatabaseStackProps} from "./database-stack-props";
import {Key} from "aws-cdk-lib/aws-kms";
import {SnapshotCredentials} from "aws-cdk-lib/aws-rds";
import {ISecurityGroup, SecurityGroup} from "aws-cdk-lib/aws-ec2";
import {CfnParameter} from "aws-cdk-lib";


export class DatabaseStack extends Stack {

    readonly ckanDatabase: aws_rds.IDatabaseInstance
    readonly ckanDatabaseSecurityGroup: ISecurityGroup
    readonly ckanDatabaseCredentials: aws_secretsmanager.ISecret
    readonly wpDatabase: aws_rds.IDatabaseInstance
    readonly wpDatabaseSecurityGroup: ISecurityGroup
    readonly wpDatabaseCredentials: aws_secretsmanager.ISecret


    constructor(scope: Construct, id: string, props: DatabaseStackProps) {
        super(scope, id, props);

        const secretEncryptionKey = Key.fromLookup(this, 'SecretEncryptionKey', {
            aliasName: 'alias/secret-encryption-key'
        })

        this.ckanDatabaseCredentials = new aws_rds.DatabaseSecret(this, 'ckanMasterCredentials', {
            username: 'ckan_admin',
            encryptionKey: secretEncryptionKey,
            secretName: `ckan-database-master-secret-${props.environment}`
        })


        this.ckanDatabaseSecurityGroup = new SecurityGroup(this, 'ckanDatabaseSecGroup', {
            vpc: props.vpc
        })

        const ckanSnapshotIdentifier = new CfnParameter(this, 'ckanSnapshotIdentifier', {
            type: 'AWS::SSM::Parameter::Value<String>',
            default: props.ckanDatabaseSnapshotParameterName
        })


        this.ckanDatabase = new aws_rds.DatabaseInstanceFromSnapshot(this, 'ckanDatabase', {
            vpc: props.vpc,
            snapshotIdentifier: ckanSnapshotIdentifier.valueAsString,
            engine: aws_rds.DatabaseInstanceEngine.postgres({version: aws_rds.PostgresEngineVersion.VER_14}),
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T4G, aws_ec2.InstanceSize.SMALL),
            allocatedStorage: 50,
            maxAllocatedStorage: 100,
            backupRetention: Duration.days(7),
            vpcSubnets: {
                subnets: props.vpc.isolatedSubnets
            },
            credentials: SnapshotCredentials.fromSecret(this.ckanDatabaseCredentials),
            securityGroups: [
                this.ckanDatabaseSecurityGroup
            ],
            allowMajorVersionUpgrade: true
        })

        const postgresHostParameter = new aws_ssm.StringParameter(this, 'postgrestHostParameter', {
            parameterName: 'postgres_host',
            stringValue: this.ckanDatabase.instanceEndpoint.hostname
        })

        this.wpDatabaseCredentials = new aws_rds.DatabaseSecret(this, 'wpMasterCredentials', {
            username: 'ckan_admin',
            encryptionKey: secretEncryptionKey,
            secretName: `wp-database-master-secret-${props.environment}`
        })


        this.wpDatabaseSecurityGroup = new SecurityGroup(this, 'wpDatabaseSecGroup', {
            vpc: props.vpc
        })


        const wpSnapshotIdentifier = new CfnParameter(this, 'wpSnapshotIdentifier', {
            type: 'AWS::SSM::Parameter::Value<String>',
            default: props.wpDatabaseSnapshotParameterName
        })

        this.wpDatabase = new aws_rds.DatabaseInstanceFromSnapshot(this, 'wpDatabase', {
            vpc: props.vpc,
            snapshotIdentifier: wpSnapshotIdentifier.valueAsString,
            engine: aws_rds.DatabaseInstanceEngine.mysql({version: aws_rds.MysqlEngineVersion.VER_8_0}),
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T4G, aws_ec2.InstanceSize.SMALL),
            allocatedStorage: 50,
            maxAllocatedStorage: 100,
            backupRetention: Duration.days(7),
            vpcSubnets: {
                subnets: props.vpc.isolatedSubnets
            },
            credentials: SnapshotCredentials.fromSecret(this.wpDatabaseCredentials),
            securityGroups: [
                this.wpDatabaseSecurityGroup
            ]
        })

        const mysqlHostParameter = new aws_ssm.StringParameter(this, 'mysqlHostParameter', {
            parameterName: 'mysql_host',
            stringValue: this.wpDatabase.instanceEndpoint.hostname
        })

    }
}