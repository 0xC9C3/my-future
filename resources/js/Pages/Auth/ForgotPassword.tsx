import React from 'react';
import Guest from '@/Layouts/Guest';
import ValidationErrors from '@/Components/ValidationErrors';
import {Head, useForm} from '@inertiajs/inertia-react';
import {FormControl, FormLabel, Input, Button} from '@chakra-ui/react';

export default function ForgotPassword({status}: any) {
    const {data, setData, post, processing, errors} = useForm({
        email: '',
    });

    const onHandleChange = (event: any) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e: any) => {
        e.preventDefault();

        post(route('password.email'));
    };

    return (
        <Guest>
            <Head title="Forgot Password" />

            <div className="mb-4 text-sm leading-normal">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            {status && (
                <div className="mb-4 font-medium text-sm text-green-600">
                    {status}
                </div>
            )}

            <ValidationErrors errors={errors} />

            <form onSubmit={submit}>
                <FormControl>
                    <FormLabel>Email</FormLabel>
                    <Input
                        required
                        name="email"
                        type="email"
                        value={data.email}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <div className="flex items-center justify-end mt-4">
                    <Button
                        className="ml-4"
                        type="submit"
                        isLoading={processing}
                    >
                        Email Password Reset Link
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
