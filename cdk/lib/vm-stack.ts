import {aws_ec2, aws_elasticloadbalancingv2, aws_iam, aws_rds, aws_s3, aws_ssm, Stack, StackProps} from "aws-cdk-lib";
import {Construct} from "constructs";
import {AutoScalingGroup} from "aws-cdk-lib/aws-autoscaling";
import {VmStackProps} from "./vm-stack-props";
import {Key} from "aws-cdk-lib/aws-kms";

export class VmStack extends Stack {
    constructor(scope: Construct, id: string, props: VmStackProps) {
        super(scope, id, props);


        const userData = aws_ec2.UserData.forLinux()

        userData.addCommands(
            'apt-get update',
            'apt-get -y dist-upgrade',
            'apt-get -y install python3 python3-pip',
            'snap install aws-cli --classic',
            'pip install ansible botocore boto3',
            'cd /root',
            'git clone https://github.com/6aika/sixodp.git',
            'cd /root/sixodp',
            'git submodule update --init --recursive',
            `aws s3 cp s3://sixodp-secrets/${props.environment}/secrets.yml /root/sixodp-secrets/${props.environment}/secrets.yml`,
            'chmod -R go-rwx /root/sixodp-secrets/*',
            'cd /root/sixodp/ansible',
            `ansible-playbook -i inventories/${props.environment} deploy-servers.yml --limit backgroundserver`,
            'echo "Bootstrap done."'
        )


        const secretEncryptionKey = Key.fromLookup(this, 'SecretEncryptionKey', {
            aliasName: 'alias/secret-encryption-key'
        })


        const role = new aws_iam.Role(this, 'InstanceRole', {
            assumedBy: new aws_iam.ServicePrincipal('ec2.amazonaws.com'),
            inlinePolicies: {
                "DecryptKmsKey": new aws_iam.PolicyDocument({
                        statements: [
                            new aws_iam.PolicyStatement({
                                    effect: aws_iam.Effect.ALLOW,
                                    actions: [
                                        "kms:Decrypt"
                                    ],
                                    resources: [
                                        secretEncryptionKey.keyArn
                                    ]
                                })
                        ]
                    })
                }
            })




        props.ckanDatabaseCredentials.grantRead(role)
        props.wpDatabaseCredentials.grantRead(role)



        const backgroundServerAsg = new AutoScalingGroup(this, 'backgroundAsg', {
            vpc: props.vpc,
            vpcSubnets:{
                subnets: props.vpc.privateSubnets
            },
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T3A, aws_ec2.InstanceSize.SMALL),
            machineImage: aws_ec2.MachineImage.genericLinux({
                'eu-west-1': 'ami-082257ce7f51354df'
            }),
            minCapacity: 1,
            maxCapacity: 1,
            userData: userData,
            role: role
        })

        const secretBucket = aws_s3.Bucket.fromBucketName(this, 'secretBucket', props.secretBucketName)

        secretBucket.grantRead(role)


        backgroundServerAsg.connections.allowTo(props.ckanDatabase, aws_ec2.Port.tcp(5432), 'background server to ckan postgres')
        backgroundServerAsg.connections.allowTo(props.wpDatabase, aws_ec2.Port.tcp(3306), 'background server to wp mysql')

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonSSMManagedInstanceCore'
        })

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonSSMReadOnlyAccess'
        })


        const privateAlb = new aws_elasticloadbalancingv2.ApplicationLoadBalancer(this, 'privateALb', {
            vpc: props.vpc,
            internetFacing: false,
            vpcSubnets: {
                subnets: props.vpc.privateSubnets
            }
        })

        const solrListener = privateAlb.addListener('solrListener', {
            port: 8983,
            protocol: aws_elasticloadbalancingv2.ApplicationProtocol.HTTP,
        })

        solrListener.addTargets('bgSolr', {
            port: 8983,
            protocol: aws_elasticloadbalancingv2.ApplicationProtocol.HTTP,
            targets: [backgroundServerAsg]
        })

        const redisListener = privateAlb.addListener('redisListener', {
            port: 6379,
            protocol: aws_elasticloadbalancingv2.ApplicationProtocol.HTTP,
        })

        redisListener.addTargets('bgRedis', {
            port: 6379,
            protocol: aws_elasticloadbalancingv2.ApplicationProtocol.HTTP,
            targets: [backgroundServerAsg]
        })


        const bgServerHostParameter = new aws_ssm.StringParameter(this, 'bgServerHostParameter', {
            parameterName: 'bg_server_host',
            stringValue: privateAlb.loadBalancerDnsName
        })

    }
}