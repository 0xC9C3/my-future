import React from 'react';
import {
    Box,
    Button,
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Input,
    Spacer,
    useToast,
} from '@chakra-ui/react';
import Dashboard from '@/Pages/Dashboard';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {DefaultProps} from '@/Types/DefaultProps';
import {Form, Formik, Field, ErrorMessage} from 'formik';
import * as yup from 'yup';
import {TOTP} from '@/Types/TOTP';
import ConfigureTOTP from '@/Components/ConfigureTOTP';

export const accountFormSchema = yup.object().shape({
    email: yup.string().email().required(),
    password: yup
        .string()
        .min(8)
        .oneOf(
            [yup.ref('password_confirmation'), null],
            'Passwords must match'
        ),
    password_confirmation: yup
        .string()
        .min(8)
        .oneOf([yup.ref('password'), null], 'Passwords must match'),
});

export default function Account(props: DefaultProps & TOTP) {
    const toast = useToast();
    const {setData, post, processing, errors} = useForm({
        email: '',
        current_password: '',
        password: '',
        password_confirmation: '',
        ...props.auth.user,
    });

    const submit = () => {
        post(route('dashboard.account.store'), {
            onSuccess: () => {
                toast({
                    title: 'Updated.',
                    status: 'success',
                    duration: 1000,
                    isClosable: true,
                });
            },
        });
    };

    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            <Box className="pb-6">
                <Heading className="py-3" size="md">
                    User
                </Heading>

                <ValidationErrors errors={errors} />

                <Formik
                    initialValues={{
                        email: '',
                        current_password: '',
                        password: '',
                        password_confirmation: '',
                        ...props.auth.user,
                    }}
                    validationSchema={accountFormSchema}
                    onSubmit={submit}
                >
                    {formikProps => (
                        <Form>
                            <Field name="email">
                                {({field, form}: any) => (
                                    <FormControl
                                        isInvalid={
                                            form.errors.email &&
                                            form.touched.email
                                        }
                                        className="py-3"
                                    >
                                        <FormLabel>Email address</FormLabel>
                                        <Input {...field} type="email" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="current_password">
                                {({field, form}: any) => (
                                    <FormControl
                                        isInvalid={
                                            form.errors.current_password &&
                                            form.touched.current_password
                                        }
                                        className="py-3"
                                    >
                                        <FormLabel>Current Password</FormLabel>
                                        <Input {...field} type="password" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="password">
                                {({field, form}: any) => (
                                    <FormControl
                                        isInvalid={
                                            form.errors.password &&
                                            form.touched.password
                                        }
                                        className="py-3"
                                    >
                                        <FormLabel>Password</FormLabel>
                                        <Input {...field} type="password" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="password_confirmation">
                                {({field, form}: any) => (
                                    <FormControl
                                        isInvalid={
                                            form.errors.password_confirmation &&
                                            form.touched.password_confirmation
                                        }
                                        className="py-3"
                                    >
                                        <FormLabel>Repeat Password</FormLabel>
                                        <Input {...field} type="password" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>

                            <Flex className="py-3">
                                <Spacer />
                                <Button
                                    colorScheme="green"
                                    type="submit"
                                    onClick={e => {
                                        e.preventDefault();
                                        setData(formikProps.values);
                                        formikProps.submitForm();
                                    }}
                                    isLoading={processing}
                                >
                                    Save
                                </Button>
                            </Flex>
                        </Form>
                    )}
                </Formik>

                <Heading className="py-3" size="md">
                    TOTP
                </Heading>

                <ConfigureTOTP totp={props.totp} />
            </Box>
        </Dashboard>
    );
}
