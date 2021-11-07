import React, {useEffect} from 'react';
import Guest from '@/Layouts/Guest';
import ValidationErrors from '@/Components/ValidationErrors';
import {Head, useForm} from '@inertiajs/inertia-react';
import {
    Button,
    Checkbox,
    Input,
    FormLabel,
    FormControl,
    Spacer,
} from '@chakra-ui/react';

export default function Login({status}: any) {
    const {data, setData, post, processing, errors, reset} = useForm({
        email: '',
        password: '',
        remember: '',
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const onHandleChange = (event: any) => {
        setData(
            event.target.name,
            event.target.type === 'checkbox'
                ? event.target.checked
                : event.target.value
        );
    };

    const submit = (e: any) => {
        e.preventDefault();

        post(route('login'));
    };

    return (
        <Guest>
            <Head title="Log in" />

            {status && (
                <div className="mb-4 font-medium text-sm text-green-600">
                    {status}
                </div>
            )}

            <ValidationErrors errors={errors} />

            <form onSubmit={submit}>
                <FormControl className="py-3">
                    <FormLabel>Email</FormLabel>
                    <Input
                        required
                        name="email"
                        type="email"
                        value={data.email}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <FormControl className="py-3">
                    <FormLabel>Password</FormLabel>
                    <Input
                        required
                        name="password"
                        type="password"
                        value={data.password}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            value={data.remember}
                            onChange={onHandleChange}
                        />

                        <span className="ml-2 text-sm">Remember me</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">
                    {/* canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="underline text-sm"
                        >
                            Forgot your password?
                        </Link>
                    )*/}

                    <Spacer />

                    <Button type="submit" isLoading={processing}>
                        Log in
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
