import {Respondent} from '@/Types/Respondent';
import {Template} from '@/Types/Template';
import {Application} from '@/Types/Application';

export type Statistic = {
    id: number;
    // eslint-disable-next-line camelcase
    created_at: string;
    // eslint-disable-next-line camelcase
    updated_at: string;
    // eslint-disable-next-line camelcase
    respondent_id: number;
    // eslint-disable-next-line camelcase
    template_id: number;
    action: 'viewed' | 'clicked' | 'sent';
    application: Application;
};
