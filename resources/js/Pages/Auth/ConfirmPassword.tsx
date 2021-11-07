import React, {useEffect} from 'react';
import Guest from '@/Layouts/Guest';
import ValidationErrors from '@/Components/ValidationErrors';
import {Head, useForm} from '@inertiajs/inertia-react';
import {Button, FormControl, FormLabel, Input} from '@chakra-ui/react';

export default function ConfirmPassword() {
    const {data, setData, post, processing, errors, reset} = useForm({
        password: '',
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const onHandleChange = (event: any) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e: any) => {
        e.preventDefault();

        post(route('password.confirm'));
    };

    return (
        <Guest>
            <Head title="Confirm Password" />

            <div className="mb-4 text-sm">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </div>

            <ValidationErrors errors={errors} />

            <form onSubmit={submit}>
                <FormControl>
                    <FormLabel>Confirm Password</FormLabel>
                    <Input
                        required
                        name="password"
                        type="password"
                        value={data.password}
                        onChange={onHandleChange}
                    />
                </FormControl>

                <div className="flex items-center justify-end mt-4">
                    <Button
                        type="submit"
                        className="ml-4"
                        isLoading={processing}
                    >
                        Confirm
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
