import {NetworkStackProps} from "./network-stack-props";
import {aws_autoscaling} from "aws-cdk-lib";

export interface LoadBalancerStackProps extends NetworkStackProps {
    webServerAsg: aws_autoscaling.AutoScalingGroup
}