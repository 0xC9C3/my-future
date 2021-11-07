import React from 'react';
import {
    Box,
    Button,
    Flex,
    FormControl,
    FormLabel,
    Heading,
    Input,
    Modal,
    ModalBody,
    ModalCloseButton,
    ModalContent,
    ModalFooter,
    ModalHeader,
    ModalOverlay,
    Select,
    Spacer,
    useDisclosure,
} from '@chakra-ui/react';
import Dashboard from '@/Pages/Dashboard';
import TemplateList from '@/Components/TemplateList';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {DefaultProps} from '@/Types/DefaultProps';
import {Templates} from '@/Types/Templates';

export default function Templates(props: DefaultProps & Templates) {
    const {isOpen, onOpen, onClose} = useDisclosure();
    const {data, setData, post, processing, errors} = useForm<{
        name: string;
        type: string;
        template: any;
    }>({
        name: '',
        type: 'portfolio',
        template: '',
    });
    const submit = (e: any) => {
        e.preventDefault();

        post(route('dashboard.templates.store'), {
            forceFormData: true,
            onSuccess: () => onClose(),
        });
    };

    const onHandleChange = (event: any) => {
        setData(
            event.target.name,
            event.target.type === 'checkbox'
                ? event.target.checked
                : event.target.value
        );
    };
    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            <ValidationErrors errors={errors} />

            <Box className="pb-6 flex-grow">
                <Heading className="py-3" size="md">
                    Portfolios
                </Heading>
                <TemplateList templates={props.templates.portfolio} />
            </Box>
            <Box className="py-6 flex-grow">
                <Heading className="py-3" size="md">
                    Emails
                </Heading>
                <TemplateList templates={props.templates.email} />
            </Box>

            <Flex className="pt-6">
                <Spacer />
                <Button colorScheme="green" onClick={onOpen}>
                    Add
                </Button>
            </Flex>

            <Modal isOpen={isOpen} onClose={onClose} isCentered>
                <ModalOverlay />
                <ModalContent>
                    <form onSubmit={submit}>
                        <ModalHeader>Create a template</ModalHeader>
                        <ModalCloseButton />
                        <ModalBody>
                            <FormControl className="py-3">
                                <FormLabel>Name</FormLabel>
                                <Input
                                    value={data.name}
                                    onChange={onHandleChange}
                                    name="name"
                                    type="text"
                                    required
                                />
                            </FormControl>
                            <FormControl className="py-3">
                                <FormLabel>Type</FormLabel>
                                <Select
                                    value={data.type}
                                    onChange={onHandleChange}
                                    name="type"
                                    required
                                >
                                    <option value="portfolio">Portfolio</option>
                                    <option value="email">Email</option>
                                </Select>
                            </FormControl>
                            <FormControl className="py-3">
                                <FormLabel>File</FormLabel>
                                <Input
                                    onChange={event => {
                                        setData(
                                            'template',
                                            event.target.files &&
                                                event.target.files.length
                                                ? event.target.files[0]
                                                : null
                                        );
                                    }}
                                    name="template"
                                    accept=".zip"
                                    type="file"
                                    required
                                />
                            </FormControl>
                        </ModalBody>
                        <ModalFooter>
                            <Button variant="ghost" mr={3} onClick={onClose}>
                                Close
                            </Button>
                            <Button isLoading={processing} type="submit">
                                Create
                            </Button>
                        </ModalFooter>
                    </form>
                </ModalContent>
            </Modal>
        </Dashboard>
    );
}
