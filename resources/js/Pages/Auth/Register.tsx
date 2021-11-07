import React, {useEffect} from 'react';
import Guest from '@/Layouts/Guest';
import ValidationErrors from '@/Components/ValidationErrors';
import {Head, Link, useForm} from '@inertiajs/inertia-react';
import {FormControl, FormLabel, Button, Input} from '@chakra-ui/react';

export default function Register() {
    const {data, setData, post, processing, errors, reset} = useForm({
        email: '',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
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

        post(route('register'));
    };

    return (
        <Guest>
            <Head title="Register" />

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

                <FormControl>
                    <FormLabel>Password</FormLabel>
                    <Input
                        required
                        name="password"
                        type="password"
                        value={data.password}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <FormControl>
                    <FormLabel>Confirm Password</FormLabel>
                    <Input
                        required
                        name="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <div className="flex items-center justify-end mt-4">
                    <Link href={route('login')} className="underline text-sm ">
                        Already registered?
                    </Link>

                    <Button
                        type="submit"
                        className="ml-4"
                        isLoading={processing}
                    >
                        Register
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
