import {NetworkStackProps} from "./network-stack-props";
import {aws_ec2, aws_efs, aws_rds, aws_secretsmanager} from "aws-cdk-lib";

export interface WebServerStackProps extends NetworkStackProps {
    secretBucketName: string,
    ckanDatabase: aws_rds.IDatabaseInstance,
    wpDatabase: aws_rds.IDatabaseInstance,
    ckanDatabaseCredentials: aws_secretsmanager.ISecret,
    wpDatabaseCredentials: aws_secretsmanager.ISecret,
    minWebServerCapacity: number,
    maxWebServerCapacity: number,
    backgroundServer: aws_ec2.IInstance,
    fileSystem: aws_efs.FileSystem
}