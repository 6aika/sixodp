import {NetworkStackProps} from "./network-stack-props";
import {aws_secretsmanager, aws_ssm} from "aws-cdk-lib";

export interface DatabaseStackProps extends NetworkStackProps {
    ckanDatabaseSnapshotParameterName: string,
    wpDatabaseSnapshotParameterName: string
}