import {Respondent} from '@/Types/Respondent';
import {Template} from '@/Types/Template';

export type Application = {
    id: number;
    // eslint-disable-next-line camelcase
    created_at: string;
    // eslint-disable-next-line camelcase
    updated_at: string;
    // eslint-disable-next-line camelcase
    email_content: string;
    // eslint-disable-next-line camelcase
    respondent_id: string;
    respondent: Respondent;
    // eslint-disable-next-line camelcase
    user_id: string;
    // eslint-disable-next-line camelcase
    template_id: number;
    template: Template;
    key: string;
};
