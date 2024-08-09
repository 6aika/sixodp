import {aws_ssm, Stack, StackProps,} from "aws-cdk-lib";
import {Construct} from "constructs";


export class ParameterStack extends Stack {

    readonly ckanDatabaseSnapshotParameterName: string
    readonly wpDatabaseSnapshotParameterName: string

    constructor(scope: Construct, id: string, props: StackProps) {
        super(scope, id, props);

        this.ckanDatabaseSnapshotParameterName = '/rds/ckan_database_snapshot_identifier';
        new aws_ssm.StringParameter(this, 'ckanDatabaseSnapshot', {
            stringValue: 'some placeholder',
            description: 'Snapshot identifier of ckan rds database',
            parameterName: this.ckanDatabaseSnapshotParameterName,
        })

        this.wpDatabaseSnapshotParameterName = '/rds/wordpress_database_snapshot_identifier';
        new aws_ssm.StringParameter(this, 'wpDatabaseSnapshot', {
            stringValue: 'some placeholder',
            description: 'Snapshot identifier of wp rds database',
            parameterName: this.wpDatabaseSnapshotParameterName,
        })

    }
}