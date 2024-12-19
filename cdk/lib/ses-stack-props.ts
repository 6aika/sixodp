import {EnvProps} from "./env-props";

export interface SesStackProps extends EnvProps {
    teamsHostParameterName: string
    teamsPathParameterName: string
}