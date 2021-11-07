import React from 'react';
import Guest from '@/Layouts/Guest';
import {Head} from '@inertiajs/inertia-react';

export default function KeyAndSessionMissing() {
    return (
        <Guest>
            <Head title="Error" />
            <p className="pb-3">
                Hey, it looks like you dont have a key. If you came here using a
                link, please make sure it contains a key.
            </p>
            <p>
                <b>
                    In case the link still does not work please contact the
                    person you received the link from.
                </b>
            </p>
        </Guest>
    );
}
