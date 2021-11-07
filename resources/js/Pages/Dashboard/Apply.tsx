import React from 'react';
import Dashboard from '@/Pages/Dashboard';
import ApplicationStepper from '@/Components/ApplicationStepper';
import {DefaultProps} from '@/Types/DefaultProps';
import {Templates} from '@/Types/Templates';
import {Alert, AlertDescription, AlertIcon, AlertTitle} from '@chakra-ui/react';
import {Link} from '@inertiajs/inertia-react';

export default function Apply(props: DefaultProps & Templates) {
    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            {!props.templates || props.templates.portfolio.length === 0 ? (
                <Alert className="my-2" status="error">
                    <AlertIcon />
                    <AlertTitle>No template configured</AlertTitle>
                    <AlertDescription>
                        Hey, it looks like you haven't configured any templates
                        yet. Head over to the{' '}
                        <Link href={route('dashboard.templates')}>
                            <b>Templates</b>
                        </Link>{' '}
                        area, to configure your first template.
                    </AlertDescription>
                </Alert>
            ) : (
                <ApplicationStepper templates={props.templates} />
            )}
        </Dashboard>
    );
}
