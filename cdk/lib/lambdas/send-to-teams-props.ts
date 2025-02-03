import {EnvProps} from "../env-props";

export interface SendToTeamsProps extends EnvProps {
    teamsHost: string,
    teamsPath: string
}