import {Statistic} from '@/Types/Statistic';

export type Statistics = {
    statistics: {
        sent: number;
        clicked: number;
        viewed: number;
        list: Statistic[];
    };
};
