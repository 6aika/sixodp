import {NetworkStackProps} from "./network-stack-props";
import {aws_rds, aws_secretsmanager} from "aws-cdk-lib";

export interface BackgroundServerStackProps extends NetworkStackProps {
    secretBucketName: string,
    ckanDatabase: aws_rds.IDatabaseInstance,
    wpDatabase: aws_rds.IDatabaseInstance,
    ckanDatabaseCredentials: aws_secretsmanager.ISecret,
    wpDatabaseCredentials: aws_secretsmanager.ISecret
}