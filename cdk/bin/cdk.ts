#!/usr/bin/env node
import 'source-map-support/register';
import * as cdk from 'aws-cdk-lib';
import {VpcStack} from "../lib/vpc-stack";
import {ParameterStack} from "../lib/parameter-stack";
import {DatabaseStack} from "../lib/database-stack";
import {KmsKeyStack} from "../lib/kms-key-stack";
import {LoadBalancerStack} from "../lib/load-balancer-stack";

const app = new cdk.App();

const stackProps = {
    account: '290365872283',
    region: 'eu-west-1',
}

const env = {
    environment: 'generic-qa',
    fqdn: 'dataportaali.com'
}

const vpcStack = new VpcStack(app, 'vpcStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    }
})

const parameterStack = new ParameterStack(app, 'parameterStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    }
})

const kmsKeyStack = new KmsKeyStack(app, 'kmsKeyStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    }
})

const databaseStack = new DatabaseStack(app, 'databaseStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    },
    ckanDatabaseSnapshotParameterName: parameterStack.ckanDatabaseSnapshotParameterName,
    wpDatabaseSnapshotParameterName: parameterStack.wpDatabaseSnapshotParameterName,
    environment: env.environment,
    fqdn: env.fqdn,
    vpc: vpcStack.vpc,
})


const loadBalancerStack = new LoadBalancerStack(app, 'loadBalancerStack', {
    env: {
        account: stackProps.account,
        region: stackProps.region
    },
    environment: env.environment,
    fqdn: env.fqdn,
    vpc: vpcStack.vpc,
})