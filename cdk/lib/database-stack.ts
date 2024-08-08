import {aws_ec2, aws_rds, Duration, Stack} from "aws-cdk-lib";
import {Construct} from "constructs";

import {DatabaseStackProps} from "./database-stack-props";
import {SubnetType} from "aws-cdk-lib/aws-ec2";


export class DatabaseStack extends Stack {

    readonly ckanDatabase: aws_rds.IDatabaseInstance
    constructor(scope: Construct, id: string, props: DatabaseStackProps) {
        super(scope, id, props);

        this.ckanDatabase = new aws_rds.DatabaseInstanceFromSnapshot(this, 'ckanDatabase', {
            vpc: props.vpc,
            snapshotIdentifier: props.ckanDatabaseSnapshot.stringValue,
            engine: aws_rds.DatabaseInstanceEngine.postgres({version: aws_rds.PostgresEngineVersion.VER_12}),
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T4G, aws_ec2.InstanceSize.SMALL),
            allocatedStorage: 50,
            backupRetention: Duration.days(7),
            vpcSubnets: {
                subnets: props.vpc.privateSubnets
            }
        })

    }
}