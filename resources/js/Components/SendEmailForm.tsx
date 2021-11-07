import React, {useState} from 'react';
import {
    Alert,
    AlertIcon,
    Box,
    Code,
    FormControl,
    FormLabel,
    Input,
    Select,
    Switch,
    Textarea,
} from '@chakra-ui/react';
import {Templates} from '@/Types/Templates';
import * as yup from 'yup';
import Editor from '@/Components/Editor';
import {Form, Field, getIn, useFormikContext, setIn} from 'formik';
import {Template} from '@/Types/Template';
import axios from 'axios';
import {html} from '@codemirror/lang-html';

export const sendEmailSchema = yup.object().shape({
    send_email: yup.string(),
    subject: yup.string(),
    content: yup.string(),
});

export const sendEmailBaseState = {
    send_email: false,
    subject: '',
    content: '',
    attach_portfolio: false,
    template_id: '',
};

export default function SendEmailForm({
    formPath,
    templates,
}: {
    formPath?: string;
} & Templates) {
    const form = useFormikContext();

    const [state, setState] = useState({
        content: undefined,
        contentLoading: false,
    });

    const handleTemplateChange = (
        e: React.ChangeEvent<HTMLSelectElement>,
        form: any
    ) => {
        const value = e.target.value;

        if (value === '') {
            return;
        }

        const templateId = parseInt(value);

        setState({
            ...state,
            content: undefined,
        });
        const template = templates.email.find(
            template => template.id == templateId
        );
        if (template) {
            // request template
            axios
                .get(route('portfolio.view') + '?key=' + template.key)
                .then(resp =>
                    form.setFieldValue(`${formPath ?? ''}content`, resp.data)
                );
        }
    };

    return (
        <Form>
            <Field name={`${formPath ?? ''}send_email`}>
                {({field, form}: any) => (
                    <FormControl
                        isInvalid={
                            getIn(form.errors, field.name) &&
                            getIn(form.touched, field.name)
                        }
                        className="py-6"
                    >
                        <FormLabel>
                            Do you want to automatically send an email?
                        </FormLabel>
                        <Select {...field} required>
                            <option value="">No</option>
                            <option value="1">Yes</option>
                        </Select>
                    </FormControl>
                )}
            </Field>

            {getIn(form.values, `${formPath ?? ''}send_email`) && (
                <>
                    <Field name={`${formPath ?? ''}subject`}>
                        {({field, form}: any) => (
                            <FormControl
                                isInvalid={
                                    getIn(form.errors, field.name) &&
                                    getIn(form.touched, field.name)
                                }
                                className="pb-6"
                            >
                                <FormLabel>Subject</FormLabel>
                                <Input {...field} type="text" />
                            </FormControl>
                        )}
                    </Field>

                    <Field name={`${formPath ?? ''}attach_portfolio`}>
                        {({field, form}: any) => (
                            <FormControl
                                isInvalid={
                                    getIn(form.errors, field.name) &&
                                    getIn(form.touched, field.name)
                                }
                                className="pb-3"
                            >
                                <FormLabel>
                                    Attach a PDF version of the portfolio?
                                </FormLabel>
                                <Switch isChecked={field.value} {...field} />
                            </FormControl>
                        )}
                    </Field>

                    <Field name={`${formPath ?? ''}use_email_template`}>
                        {({field, form}: any) => (
                            <FormControl
                                isInvalid={
                                    getIn(form.errors, field.name) &&
                                    getIn(form.touched, field.name)
                                }
                                className="pb-6"
                            >
                                <FormLabel>
                                    Do you want to use a template?
                                </FormLabel>
                                <Select {...field}>
                                    <option value="">No</option>
                                    <option value="1">Yes</option>
                                </Select>
                            </FormControl>
                        )}
                    </Field>

                    <Alert className="mb-6" status="info">
                        <AlertIcon />
                        You can use <Code className="mx-3">
                            PORTFOLIO_LINK
                        </Code>{' '}
                        to automatically substitute the portfolio link at that
                        place!
                    </Alert>

                    <Alert className="mb-6" status="info">
                        <AlertIcon />
                        You can use{' '}
                        <Code className="mx-3">PORTFOLIO_EMAIL_LINK</Code> to
                        automatically substitute the link to the tracking pixel
                        at that place!
                    </Alert>

                    {getIn(
                        form.values,
                        `${formPath ?? ''}use_email_template`
                    ) ? (
                        <>
                            <Field name={`${formPath ?? ''}template_id`}>
                                {({field, form}: any) => (
                                    <FormControl
                                        isInvalid={
                                            getIn(form.errors, field.name) &&
                                            getIn(form.touched, field.name)
                                        }
                                        className="pb-6"
                                    >
                                        <FormLabel>Template</FormLabel>
                                        <Select
                                            {...field}
                                            onChange={e => {
                                                handleTemplateChange(e, form);
                                                form.handleChange(e);
                                            }}
                                        >
                                            <option value="">-</option>
                                            {templates.email.map(
                                                (
                                                    template: Template,
                                                    idx: number
                                                ) => {
                                                    return (
                                                        <option
                                                            key={idx}
                                                            value={template.id}
                                                        >
                                                            {template.name}
                                                        </option>
                                                    );
                                                }
                                            )}
                                        </Select>
                                    </FormControl>
                                )}
                            </Field>
                            {getIn(form.values, `${formPath ?? ''}content`) &&
                                getIn(
                                    form.values,
                                    `${formPath ?? ''}template_id`
                                ) && (
                                    <Field name={`${formPath ?? ''}content`}>
                                        {({field, form}: any) => (
                                            <Box className="pb-6">
                                                <Editor
                                                    extensions={[html()]}
                                                    value={field.value}
                                                    onChange={(value: string) =>
                                                        form.setFieldValue(
                                                            `${
                                                                formPath ?? ''
                                                            }content`,
                                                            value
                                                        )
                                                    }
                                                />
                                            </Box>
                                        )}
                                    </Field>
                                )}
                        </>
                    ) : (
                        <Field name={`${formPath ?? ''}content`}>
                            {({field, form}: any) => (
                                <FormControl
                                    isInvalid={
                                        getIn(form.errors, field.name) &&
                                        getIn(form.touched, field.name)
                                    }
                                    className="pb-6"
                                >
                                    <FormLabel>Type your message</FormLabel>
                                    <Textarea
                                        {...field}
                                        placeholder="Type your message here"
                                        size="sm"
                                    />
                                </FormControl>
                            )}
                        </Field>
                    )}
                </>
            )}
        </Form>
    );
}
