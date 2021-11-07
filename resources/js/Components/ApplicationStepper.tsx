import React from 'react';
import {Box, Button, Flex, Heading, Spacer, useToast} from '@chakra-ui/react';
import {useForm} from '@inertiajs/inertia-react';
import {Step, Steps, useSteps} from 'chakra-ui-steps';
import AssignPortfolioForm, {
    assignPortfolioBaseState,
    assignPortfolioSchema,
} from '@/Components/AssignPortfolioForm';
import SendEmailForm, {
    sendEmailBaseState,
    sendEmailSchema,
} from '@/Components/SendEmailForm';
import {Templates} from '@/Types/Templates';
import CreateRespondentForm, {
    createRespondentBaseState,
    createRespondentSchema,
} from '@/Components/CreateRespondentForm';
import ValidationErrors from '@/Components/ValidationErrors';
import {EmailIcon} from '@chakra-ui/icons';
import {Formik, Form} from 'formik';
import * as yup from 'yup';

const FORM_BASE_STATE = {
    respondent: createRespondentBaseState,
    portfolio: assignPortfolioBaseState,
    email: sendEmailBaseState,
};

const FORM_BASE_VALIDATION = yup.object().shape({
    respondent: createRespondentSchema,
});

const steps = [
    {name: 'respondent', schema: createRespondentSchema},
    {name: 'portfolio', schema: assignPortfolioSchema},
    {name: 'email', schema: sendEmailSchema},
];

export default function ApplicationStepper({templates}: Templates) {
    const toast = useToast();
    const {data, setData, post, processing, errors} = useForm(FORM_BASE_STATE);

    const {nextStep, prevStep, reset, activeStep} = useSteps({
        initialStep: 0,
    });

    // prevent unintended navigation away via formik dirty
    // @TODO

    const currentStepValid = (values: any) => {
        const step = steps[activeStep];
        try {
            step.schema.validateSync(values[step.name]);
            return true;
        } catch (e) {
            return false;
        }
    };

    const submit = (values: any, form: any) => {
        post(route('dashboard.apply.save'), {
            onSuccess: () => {
                toast({
                    title: `Application ${
                        data.email.send_email ? 'sent' : 'saved'
                    }!`,
                    status: 'success',
                    duration: 1000,
                    isClosable: true,
                });
                form.resetForm();
                reset();
            },
        });
    };

    return (
        <Formik
            validationSchema={FORM_BASE_VALIDATION}
            initialValues={FORM_BASE_STATE}
            onSubmit={submit}
        >
            {props => (
                <Box>
                    <Steps activeStep={activeStep}>
                        <Step label="Who?" key={1}>
                            <CreateRespondentForm formPath="respondent." />
                        </Step>
                        <Step label="Which portfolio?" key={2}>
                            <AssignPortfolioForm
                                formPath="portfolio."
                                templates={templates}
                            />
                        </Step>
                        <Step label="Send an email?" key={3}>
                            <SendEmailForm
                                formPath="email."
                                templates={templates}
                            />
                        </Step>
                        <Step label="Apply!" key={4}>
                            <Form>
                                <Box className="mx-auto py-6">
                                    <Box className="py-3">
                                        <ValidationErrors errors={errors} />

                                        <Heading size="md">Who?</Heading>
                                        <p>
                                            <b>Name</b>{' '}
                                            {props.values.respondent.name}
                                        </p>
                                        <p>
                                            <b>Email</b>{' '}
                                            {props.values.respondent.email}
                                        </p>
                                        <p>
                                            <b>Active</b>{' '}
                                            {props.values.respondent.active
                                                ? 'Yes'
                                                : 'No'}
                                        </p>
                                    </Box>
                                    <Box className="pb-3">
                                        <Heading size="md">
                                            Which portfolio?
                                        </Heading>
                                        <p>
                                            <b>Portfolio</b>{' '}
                                            {props.values.portfolio.id}
                                        </p>
                                    </Box>
                                    <Box className="pb-3">
                                        <Heading size="md">
                                            Send an email?
                                        </Heading>
                                        <p>
                                            <b>Yes/No?</b>{' '}
                                            {props.values.email.send_email
                                                ? 'Yes'
                                                : 'No'}
                                        </p>
                                        {props.values.email.send_email && (
                                            <>
                                                <p>
                                                    <b>Subject</b>{' '}
                                                    {props.values.email.subject}
                                                </p>
                                                <p>
                                                    <b>Content</b>
                                                </p>
                                                <div
                                                    dangerouslySetInnerHTML={{
                                                        __html: props.values
                                                            .email.content,
                                                    }}
                                                />
                                            </>
                                        )}
                                    </Box>
                                </Box>
                                <Flex className="pb-3">
                                    <Spacer />
                                    <Button
                                        leftIcon={<EmailIcon />}
                                        colorScheme="green"
                                        type="submit"
                                        isLoading={processing}
                                        isDisabled={!props.isValid}
                                    >
                                        Apply!
                                    </Button>
                                    <Spacer />
                                </Flex>
                            </Form>
                        </Step>
                    </Steps>
                    <Flex>
                        {activeStep > 0 && (
                            <Button onClick={prevStep}>Previous</Button>
                        )}
                        <Spacer />
                        {activeStep < 3 && (
                            <Button
                                isDisabled={!currentStepValid(props.values)}
                                onClick={() => {
                                    setData(props.values);
                                    nextStep();
                                }}
                            >
                                Next
                            </Button>
                        )}
                    </Flex>
                </Box>
            )}
        </Formik>
    );
}
