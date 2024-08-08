import {EnvProps} from "./env-props";
import {IVpc} from "aws-cdk-lib/aws-ec2";

export interface NetworkStackProps extends EnvProps {
    vpc: IVpc
}