import {
    aws_autoscaling,
    aws_ec2, aws_efs,
    aws_elasticloadbalancingv2,
    aws_iam,
    aws_rds,
    aws_s3,
    aws_ssm,
    Stack,
    StackProps
} from "aws-cdk-lib";
import {Construct} from "constructs";
import {AutoScalingGroup} from "aws-cdk-lib/aws-autoscaling";
import {WebServerStackProps} from "./web-server-stack-props";
import {Key} from "aws-cdk-lib/aws-kms";
import {EbsDeviceVolumeType} from "aws-cdk-lib/aws-ec2";

export class WebServerStack extends Stack {
    readonly webServerAsg: aws_autoscaling.AutoScalingGroup;

    constructor(scope: Construct, id: string, props: WebServerStackProps) {
        super(scope, id, props);


        const webServerUserData = aws_ec2.UserData.forLinux()

        webServerUserData.addCommands(
            'apt-get update',
            'apt-get -y dist-upgrade',
            'apt-get -y install python3 python3-pip cargo pkg-config libssl-dev',
            'snap install aws-cli --classic',
            'pip install ansible botocore boto3',
            'cd /root',
            'git clone https://github.com/aws/efs-utils',
            'cd efs-utils',
            './build-deb.sh',
            'apt-get -y install ./build/amazon-efs-utils*deb',
            `echo ${props.fileSystem.fileSystemId}:/ /mnt efs _netdev,noresvport,tls,iam 0 0 >> /etc/fstab`,
            `echo ${props.fileSystem.fileSystemId}:/opt_datacatalog_data_ckan /opt/datacatalog/data/ckan efs _netdev,noresvport,tls,iam 0 0 >> /etc/fstab`,
            'mount /mnt',
            'mkdir -p /opt/datacatalog/data/ckan /mnt/opt_datacatalog_data_ckan',
            'mount /opt/datacatalog/data/ckan',
            'install -d -o www-data -g www-data /mnt/wp-uploads',
            'cd /root',
            'git clone https://github.com/6aika/sixodp.git',
            'cd /root/sixodp',
            'git submodule update --init --recursive',
            `aws s3 cp s3://sixodp-secrets/${props.environment}/secrets.yml /root/sixodp-secrets/${props.environment}/secrets.yml`,
            'chmod -R go-rwx /root/sixodp-secrets/*',
            'cd /root/sixodp/ansible',
            `ansible-playbook -i inventories/${props.environment} deploy-servers.yml --limit webserver`,
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

        const secretBucket = aws_s3.Bucket.fromBucketName(this, 'secretBucket', props.secretBucketName)

        secretBucket.grantRead(role)

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonSSMManagedInstanceCore'
        })

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonSSMReadOnlyAccess'
        })

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonElasticFileSystemClientFullAccess'
        })

        role.addManagedPolicy({
            managedPolicyArn: 'arn:aws:iam::aws:policy/AmazonElasticFileSystemsUtils'
        })

        this.webServerAsg = new AutoScalingGroup(this, 'webAsg', {
            vpc: props.vpc,
            vpcSubnets:{
                subnets: props.vpc.privateSubnets
            },
            instanceType: aws_ec2.InstanceType.of(aws_ec2.InstanceClass.T3A, aws_ec2.InstanceSize.SMALL),
            machineImage: aws_ec2.MachineImage.genericLinux({
                'eu-west-1': 'ami-082257ce7f51354df'
            }),
            minCapacity: props.minWebServerCapacity,
            maxCapacity: props.maxWebServerCapacity,
            userData: webServerUserData,
            role: role,
            blockDevices : [
                {
                    deviceName: '/dev/sda1',
                    volume: aws_autoscaling.BlockDeviceVolume.ebs(30, {
                        volumeType: aws_autoscaling.EbsDeviceVolumeType.GP3,
                        deleteOnTermination: true,
                    })
                }
            ]
        })


        this.webServerAsg.connections.allowTo(props.backgroundServer, aws_ec2.Port.tcp(8983), 'Solr connection from web server')
        this.webServerAsg.connections.allowTo(props.backgroundServer, aws_ec2.Port.tcp(6379), 'Redis connection from web server')

        this.webServerAsg.connections.allowTo(props.ckanDatabase, aws_ec2.Port.tcp(5432), 'web server to ckan postgres')
        this.webServerAsg.connections.allowTo(props.wpDatabase, aws_ec2.Port.tcp(3306), 'web server to wp mysql')

        this.webServerAsg.connections.allowTo(props.fileSystem, aws_ec2.Port.tcp(2049), 'web server to efs')




    }
}