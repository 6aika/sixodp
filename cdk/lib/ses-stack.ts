import {
    aws_iam,
    aws_route53,
    aws_s3,
    aws_ses,
    aws_ses_actions,
    aws_sns,
    aws_ssm,
    custom_resources,
    Stack
} from "aws-cdk-lib";
import {Construct} from "constructs";
import {EnvProps} from "./env-props";
import {RetentionDays} from "aws-cdk-lib/aws-logs";
import {HostedZone, PublicHostedZone} from "aws-cdk-lib/aws-route53";

export class SesStack extends Stack {
    constructor(scope: Construct, id: string, props: EnvProps) {
        super(scope, id, props);

        const identity = new aws_ses.EmailIdentity(this, 'EmailIdentity', {
            identity: aws_ses.Identity.publicHostedZone(HostedZone.fromLookup(this, 'HostedZone', {
                domainName: props.domain
            })),
        })


        const receivedMailBucket = new aws_s3.Bucket(this, 'ReceivedMail', {
            bucketName: `${props.environment}-ses-received-mail`
        })


        const receivedMailSnsTopic = new aws_sns.Topic(this, 'ReceivedMailSns', {

        })

        const receiptRuleSet = new aws_ses.ReceiptRuleSet(this, 'ReceiptRuleSet', {
            rules: [{
                    actions: [new aws_ses_actions.S3({
                        bucket: receivedMailBucket,
                        topic: receivedMailSnsTopic
                    }),
                    ]
                }
            ]
        })

        // hack to set receiptRuleSet active, ref: https://github.com/aws/aws-cdk/issues/10321#issuecomment-2051486380
        const setActiveRuleSetSdkCall: custom_resources.AwsSdkCall = {
            service: 'SES',
            action: 'setActiveReceiptRuleSet',
            physicalResourceId: custom_resources.PhysicalResourceId.of('SesCustomResource'),
            parameters: {
                RuleSetName: receiptRuleSet.receiptRuleSetName
            }
        }

        new custom_resources.AwsCustomResource(this, "setActiveReceiptRuleSetCustomResource", {
            onCreate: setActiveRuleSetSdkCall,
            onUpdate: setActiveRuleSetSdkCall,
            logRetention: RetentionDays.ONE_WEEK,
            policy: custom_resources.AwsCustomResourcePolicy.fromStatements([
                new aws_iam.PolicyStatement({
                    sid: "SesCustomResourceSetActiveRuleSet",
                    effect: aws_iam.Effect.ALLOW,
                    actions: ['ses:SetActiveReceiptRuleSet'],
                    resources: ['*']
                })
            ])
        })


        const smtpUserNameParameter = new aws_ssm.StringParameter(this, 'smtpUserNameParameter', {
            parameterName: `/${props.environment}/smtp/username`,
            stringValue: "placeholder",
            description: "Username for smtp"
        })

        const smtpPasswordParameter = new aws_ssm.StringParameter(this, 'smtpPasswordParameter', {
            parameterName: `/${props.environment}/smtp/password`,
            stringValue: "placeholder",
            description: "Password for smtp"
        })
    }
}