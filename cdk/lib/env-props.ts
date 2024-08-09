import {StackProps} from "aws-cdk-lib";

export interface EnvProps extends StackProps {
    environment: string,
    fqdn: string
}