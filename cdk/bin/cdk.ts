#!/usr/bin/env node
import 'source-map-support/register';
import * as cdk from 'aws-cdk-lib';
import {VpcStack} from "../lib/vpc-stack";

const app = new cdk.App();

const stackProps = {
    account: '290365872283',
    region: 'eu-west-1',
}

const vpcStack = new VpcStack(app, 'vpcStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    }
})