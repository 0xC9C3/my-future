import React from 'react';
import {TOTP} from '@/Types/TOTP';
import {
    Alert,
    AlertIcon,
    AlertTitle,
    Box,
    Button,
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Input,
    Spacer,
    useToast,
} from '@chakra-ui/react';
import {Link, useForm} from '@inertiajs/inertia-react';
import {DownloadIcon} from '@chakra-ui/icons';
import ValidationErrors from '@/Components/ValidationErrors';
import {ErrorMessage, Field, Form, Formik} from 'formik';
import {accountFormSchema} from '@/Pages/Dashboard/Account';
import * as yup from 'yup';

export const otpSchema = yup.object().shape({
    code: yup.string().length(6).required(),
});

export default function ConfigureTOTP({totp}: TOTP) {
    const toast = useToast();

    const {setData, post, processing, errors} = useForm({
        code: '',
    });
    const submit = () => {
        post(route('dashboard.account.verify-totp'), {
            onSuccess: () => {
                toast({
                    title: 'Verified.',
                    status: 'success',
                    duration: 1000,
                    isClosable: true,
                });
            },
        });
    };
    return (
        <Box>
            {totp.enabled ? (
                totp.verified ? (
                    <Box>
                        <Flex className="py-3">
                            <Button
                                colorScheme="blue"
                                leftIcon={<DownloadIcon />}
                                aria-label="export"
                                href={route('dashboard.account.recovery-codes')}
                                target="_blank"
                                as="a"
                                cursor="pointer"
                            >
                                Download recovery codes
                            </Button>
                            <Spacer />
                            <Link
                                href={route('two-factor.recovery-codes')}
                                method="post"
                                as="button"
                                onSuccess={() => {
                                    toast({
                                        title: 'Reset recovery codes',
                                        status: 'success',
                                        duration: 1000,
                                        isClosable: true,
                                    });
                                }}
                            >
                                <Button colorScheme="red" as="div">
                                    Reset recovery codes
                                </Button>
                            </Link>
                            <Link
                                href={route('two-factor.disable')}
                                method="delete"
                                as="button"
                            >
                                <Button
                                    className="ml-4"
                                    colorScheme="red"
                                    as="div"
                                >
                                    Disable TOTP
                                </Button>
                            </Link>
                        </Flex>
                    </Box>
                ) : (
                    <Box>
                        <Alert className="my-3" status="error">
                            <AlertIcon />
                            <AlertTitle>
                                Enter your OTP code below to confirm your TOTP
                                setup.
                            </AlertTitle>
                        </Alert>
                        <Box className="text-center pt-3">
                            <Box
                                className="p-3 bg-white inline-block"
                                dangerouslySetInnerHTML={{
                                    __html: totp.svg ?? '',
                                }}
                            />
                        </Box>

                        <ValidationErrors errors={errors} />

                        <Formik
                            initialValues={{
                                code: '',
                            }}
                            validationSchema={otpSchema}
                            onSubmit={submit}
                        >
                            {formikProps => (
                                <Form>
                                    <Field name="code">
                                        {({field, form}: any) => (
                                            <FormControl
                                                isInvalid={
                                                    form.errors.code &&
                                                    form.touched.code
                                                }
                                                className="py-3"
                                            >
                                                <FormLabel>OTP Code</FormLabel>
                                                <Input {...field} type="text" />
                                                <FormErrorMessage>
                                                    <ErrorMessage
                                                        name={field.name}
                                                    />
                                                </FormErrorMessage>
                                            </FormControl>
                                        )}
                                    </Field>

                                    <Flex className="py-3">
                                        <Link
                                            href={route('two-factor.disable')}
                                            method="delete"
                                            as="button"
                                        >
                                            <Button colorScheme="red" as="div">
                                                Abort TOTP setup
                                            </Button>
                                        </Link>
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
                                            Verify
                                        </Button>
                                    </Flex>
                                </Form>
                            )}
                        </Formik>
                    </Box>
                )
            ) : (
                <Flex>
                    <Link
                        href={route('two-factor.enable')}
                        method="post"
                        as="button"
                    >
                        <Button as="div" colorScheme="green">
                            Enable TOTP
                        </Button>
                    </Link>
                </Flex>
            )}
        </Box>
    );
}
