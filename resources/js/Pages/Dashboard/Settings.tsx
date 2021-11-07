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
    Select,
    Spacer,
    Switch,
    useToast,
} from '@chakra-ui/react';
import Dashboard from '@/Pages/Dashboard';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {DefaultProps} from '@/Types/DefaultProps';
import {Settings} from '@/Types/Settings';
import {Form, Formik, Field, ErrorMessage} from 'formik';
import * as yup from 'yup';

export const settingsFormSchema = yup.object().shape({
    smtp_host: yup.string(),
    smtp_port: yup.string(),
    smtp_username: yup.string(),
    smtp_password: yup.string(),
    smtp_encryption: yup.string(),
    smtp_from_address: yup.string(),
    smtp_from_name: yup.string(),
    track_web_access: yup.boolean(),
    track_email_access: yup.boolean(),
});

export default function Settings(props: DefaultProps & Settings) {
    const toast = useToast();
    const {setData, post, processing, errors} = useForm({
        smtp_host: '',
        smtp_port: '',
        smtp_username: '',
        smtp_password: '',
        smtp_encryption: '',
        smtp_from_address: '',
        smtp_from_name: '',
        track_web_access: false,
        track_email_access: false,
        ...props.settings,
    });

    const submit = () => {
        post(route('dashboard.settings.store'), {
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
            <Formik
                initialValues={{
                    smtp_host: '',
                    smtp_port: '',
                    smtp_username: '',
                    smtp_password: '',
                    smtp_encryption: '',
                    smtp_from_address: '',
                    smtp_from_name: '',
                    track_web_access: false,
                    track_email_access: false,
                    ...props.settings,
                }}
                validationSchema={settingsFormSchema}
                onSubmit={submit}
            >
                {props => (
                    <Form>
                        <Box className="pb-6">
                            <Heading className="py-3" size="md">
                                SMTP
                            </Heading>

                            <ValidationErrors errors={errors} />

                            <Field name="smtp_host">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Host</FormLabel>
                                        <Input {...field} type="text" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_port">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Port</FormLabel>
                                        <Input
                                            {...field}
                                            min="1"
                                            max="65535"
                                            type="number"
                                        />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_username">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Username</FormLabel>
                                        <Input {...field} type="text" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_password">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Password</FormLabel>
                                        <Input {...field} type="password" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_encryption">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Encryption</FormLabel>
                                        <Select {...field}>
                                            <option value="">None</option>
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                        </Select>
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_from_address">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>From Address</FormLabel>
                                        <Input {...field} type="email" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="smtp_from_name">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>From Name</FormLabel>
                                        <Input {...field} type="text" />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                        </Box>

                        <Box className="py-6">
                            <Heading className="py-3" size="md">
                                Tracking
                            </Heading>
                            <Field name="track_web_access">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>Track web access</FormLabel>
                                        <Switch
                                            isChecked={field.value}
                                            {...field}
                                        />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Field name="track_email_access">
                                {({field}: any) => (
                                    <FormControl className="py-3">
                                        <FormLabel>
                                            Track email access
                                        </FormLabel>
                                        <Switch
                                            isChecked={field.value}
                                            {...field}
                                        />
                                        <FormErrorMessage>
                                            <ErrorMessage name={field.name} />
                                        </FormErrorMessage>
                                    </FormControl>
                                )}
                            </Field>
                            <Flex>
                                <Spacer />
                                <Button
                                    colorScheme="green"
                                    type="submit"
                                    isLoading={processing}
                                    onClick={e => {
                                        e.preventDefault();
                                        setData(props.values);
                                        props.submitForm();
                                    }}
                                >
                                    Save
                                </Button>
                            </Flex>
                        </Box>
                    </Form>
                )}
            </Formik>
        </Dashboard>
    );
}
