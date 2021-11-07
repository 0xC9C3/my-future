import React from 'react';
import Guest from '@/Layouts/Guest';
import {Head} from '@inertiajs/inertia-react';

export default function KeyInvalid() {
    return (
        <Guest>
            <Head title="Error" />
            <p>
                Hey, it looks like the key is not recognised. Please make sure
                you are using the correct link and if not contact the person,
                from which you received the link from.
            </p>
        </Guest>
    );
}
