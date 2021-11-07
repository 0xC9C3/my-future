import {User} from '@/Types/User';

export type Auth = {
    totp: {
        enabled: boolean;
        svg: string | null;
    };
    user: User | null;
};
