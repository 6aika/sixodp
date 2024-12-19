import {Construct} from "constructs";
import {aws_lambda} from "aws-cdk-lib";
import {NodejsFunction} from "aws-cdk-lib/aws-lambda-nodejs";
import {SendToTeamsProps} from "./send-to-teams-props";

export class SendToTeams extends Construct {
    readonly lambda: NodejsFunction
    constructor(scope: Construct, id: string, props: SendToTeamsProps) {
        super(scope, id);

        this.lambda = new NodejsFunction(this, "function", {
            environment: {
                ENVIRONMENT: props.environment,
                TEAMS_HOST: props.teamsHost,
                TEAMS_PATH: props.teamsPath
            },
            runtime: aws_lambda.Runtime.NODEJS_20_X,
        })
    }
}