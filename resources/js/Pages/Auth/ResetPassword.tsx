import React, {useEffect} from 'react';
import Guest from '@/Layouts/Guest';
import ValidationErrors from '@/Components/ValidationErrors';
import {Head, useForm} from '@inertiajs/inertia-react';
import {
    Button,
    FormControl,
    FormHelperText,
    FormLabel,
    Input,
} from '@chakra-ui/react';

export default function ResetPassword({token, email}: any) {
    const {data, setData, post, processing, errors, reset} = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const onHandleChange = (event: any) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e: any) => {
        e.preventDefault();

        post(route('password.update'));
    };

    return (
        <Guest>
            <Head title="Reset Password" />

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
                    <FormHelperText>Confirm Password</FormHelperText>
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
                    <FormHelperText>Password</FormHelperText>
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
                    <FormHelperText>Confirm Password</FormHelperText>
                </FormControl>

                <div className="flex items-center justify-end mt-4">
                    <Button
                        type="submit"
                        className="ml-4"
                        isLoading={processing}
                    >
                        Reset Password
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
