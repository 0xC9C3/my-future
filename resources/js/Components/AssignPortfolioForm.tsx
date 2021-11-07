import React from 'react';
import {
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    IconButton,
    Select,
} from '@chakra-ui/react';
import {Templates} from '@/Types/Templates';
import {Template} from '@/Types/Template';
import * as yup from 'yup';
import {ViewIcon} from '@chakra-ui/icons';
import {Form, Field, getIn, ErrorMessage} from 'formik';

export const assignPortfolioSchema = yup.object().shape({
    id: yup.string().required(),
});

export const assignPortfolioBaseState = {
    id: '',
};

export default function AssignPortfolioForm({
    formPath,
    templates,
}: {
    formPath?: string;
} & Templates) {
    return (
        <Form>
            <Field name={`${formPath ?? ''}id`}>
                {({field, form}: any) => (
                    <FormControl
                        isInvalid={
                            getIn(form.errors, field.name) &&
                            getIn(form.touched, field.name)
                        }
                        className="py-6"
                    >
                        <FormLabel>Template</FormLabel>
                        <Flex>
                            <Select {...field} required>
                                <option value="">-</option>
                                {templates.portfolio.map(
                                    (portfolio: Template, idx: number) => {
                                        return (
                                            <option
                                                key={idx}
                                                value={portfolio.id}
                                            >
                                                {portfolio.name}
                                            </option>
                                        );
                                    }
                                )}
                            </Select>
                            <IconButton
                                icon={<ViewIcon />}
                                aria-label="preview"
                                className="ml-6"
                                isDisabled={!field.value}
                                style={{
                                    pointerEvents: field.value
                                        ? 'auto'
                                        : 'none',
                                }}
                                href={
                                    route('portfolio.view') +
                                    '?key=' +
                                    templates.portfolio.find(
                                        template => template.id == field.value
                                    )?.key
                                }
                                target="_blank"
                                as="a"
                                cursor="pointer"
                            />
                        </Flex>
                        <FormErrorMessage>
                            <ErrorMessage name={field.name} />
                        </FormErrorMessage>
                    </FormControl>
                )}
            </Field>
        </Form>
    );
}
