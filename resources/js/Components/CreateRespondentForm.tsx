import React from 'react';
import {
    FormControl,
    FormErrorMessage,
    FormLabel,
    Input,
    Switch,
} from '@chakra-ui/react';
import * as yup from 'yup';
import {Form, Field, ErrorMessage, getIn} from 'formik';

export const createRespondentSchema = yup.object().shape({
    name: yup.string().required(),
    email: yup.string().email().required(),
});

export const createRespondentBaseState = {
    name: '',
    email: '',
    active: true,
};

export default function CreateRespondentForm({formPath}: {formPath?: string}) {
    return (
        <Form>
            <Field name={`${formPath ?? ''}name`}>
                {({field, form}: any) => (
                    <FormControl
                        isInvalid={
                            getIn(form.errors, field.name) &&
                            getIn(form.touched, field.name)
                        }
                        className="py-6"
                    >
                        <FormLabel>Name</FormLabel>
                        <Input {...field} required />
                        <FormErrorMessage>
                            <ErrorMessage name={field.name} />
                        </FormErrorMessage>
                    </FormControl>
                )}
            </Field>
            <Field name={`${formPath ?? ''}email`}>
                {({field, form}: any) => (
                    <FormControl
                        isInvalid={
                            getIn(form.errors, field.name) &&
                            getIn(form.touched, field.name)
                        }
                        className="pb-6"
                    >
                        <FormLabel>Email</FormLabel>
                        <Input {...field} required />
                        <FormErrorMessage>
                            <ErrorMessage name={field.name} />
                        </FormErrorMessage>
                    </FormControl>
                )}
            </Field>
            <Field name={`${formPath ?? ''}active`}>
                {({field, form}: any) => (
                    <FormControl
                        isInvalid={
                            getIn(form.errors, field.name) &&
                            getIn(form.touched, field.name)
                        }
                        className="pb-6"
                    >
                        <FormLabel>Active</FormLabel>
                        <Switch isChecked={field.value} {...field} />
                        <FormErrorMessage>
                            <ErrorMessage name={field.name} />
                        </FormErrorMessage>
                    </FormControl>
                )}
            </Field>
        </Form>
    );
}
