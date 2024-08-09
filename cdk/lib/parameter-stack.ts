import {aws_rds, aws_secretsmanager, aws_ssm, Stack, StackProps,} from "aws-cdk-lib";
import {Construct} from "constructs";


export class ParameterStack extends Stack {

    readonly ckanDatabaseSnapshot: aws_ssm.IStringParameter
    readonly ckanDatabaseMasterCredentials: aws_secretsmanager.ISecret

    constructor(scope: Construct, id: string, props: StackProps) {
        super(scope, id, props);

        this.ckanDatabaseSnapshot = new aws_ssm.StringParameter(this, 'ckanDatabaseSnapshot', {
            stringValue: 'some placeholder',
            description: 'Snapshot identifier of ckan rds database',
            parameterName: '/rds/ckan_database_snapshot_identifier',
        })


    }
}