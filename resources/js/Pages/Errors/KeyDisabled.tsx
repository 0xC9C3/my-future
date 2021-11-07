import React from 'react';
import Guest from '@/Layouts/Guest';
import {Head} from '@inertiajs/inertia-react';

export default function KeyDisabled() {
    return (
        <Guest>
            <Head title="Error" />
            <p>
                Hey, it looks like the link you used is not properly configured
                or was disabled. Please contact the person you received the link
                from.
            </p>
        </Guest>
    );
}
