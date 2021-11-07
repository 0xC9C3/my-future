import React, {useState} from 'react';
import {
    Accordion,
    Button,
    Flex,
    Heading,
    IconButton,
    Spacer,
    Switch,
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
    useDisclosure,
    useToast,
} from '@chakra-ui/react';
import {AddIcon, CopyIcon, DeleteIcon, RepeatIcon} from '@chakra-ui/icons';
import Dashboard from '@/Pages/Dashboard';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {Respondent} from '@/Types/Respondent';
import {DefaultProps} from '@/Types/DefaultProps';
import {Respondents} from '@/Types/Respondents';
import DeleteRespondentModal from '@/Components/DeleteRespondentModal';
import CreateRespondentModal from '@/Components/CreateRespondentModal';

export default function Respondents(props: DefaultProps & Respondents) {
    const toast = useToast();
    const deleteDisclosure = useDisclosure();
    const createDisclosure = useDisclosure();
    const [respondent, setRespondent] = useState<Respondent>();
    const {data, post, errors} = useForm<{
        respondent: undefined | number;
    }>({
        respondent: undefined,
    });
    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            <Heading className="py-3" size="md">
                Respondents
            </Heading>

            <ValidationErrors errors={errors} />

            <Accordion className="flex-grow">
                <Table variant="simple">
                    <Thead>
                        <Tr>
                            <Th>Created</Th>
                            <Th>Name</Th>
                            <Th>Email</Th>
                            <Th>Active</Th>
                            <Th>Delete</Th>
                        </Tr>
                    </Thead>
                    <Tbody>
                        {props.respondents.map(
                            (val: Respondent, idx: number) => {
                                return (
                                    <Tr key={idx}>
                                        <Td>
                                            {new Date(
                                                val.created_at
                                            ).toDateString()}
                                        </Td>
                                        <Td>{val.name}</Td>
                                        <Td>{val.email}</Td>
                                        <Td>
                                            <Switch
                                                isChecked={val.active}
                                                onChange={() => {
                                                    data.respondent = val.id;
                                                    post(
                                                        route(
                                                            'dashboard.respondents.toggle'
                                                        ),
                                                        {
                                                            onSuccess: () => {
                                                                toast({
                                                                    title:
                                                                        (val.active
                                                                            ? 'Disabled '
                                                                            : 'Enabled ') +
                                                                        val.name,
                                                                    status: 'success',
                                                                    duration: 1000,
                                                                    isClosable:
                                                                        true,
                                                                });
                                                            },
                                                        }
                                                    );
                                                }}
                                            />
                                        </Td>
                                        <Td>
                                            <IconButton
                                                onClick={() => {
                                                    setRespondent(val);
                                                    deleteDisclosure.onOpen();
                                                }}
                                                colorScheme="red"
                                                icon={<DeleteIcon />}
                                                aria-label="delete"
                                            />
                                        </Td>
                                    </Tr>
                                );
                            }
                        )}
                    </Tbody>
                </Table>
            </Accordion>

            <Flex className="pt-6">
                <Spacer />
                <Button colorScheme="green" onClick={createDisclosure.onOpen}>
                    Add
                </Button>
            </Flex>

            <CreateRespondentModal
                isOpen={createDisclosure.isOpen}
                onClose={createDisclosure.onClose}
            />

            {respondent && (
                <DeleteRespondentModal
                    respondent={respondent}
                    isOpen={deleteDisclosure.isOpen}
                    onClose={() => {
                        setRespondent(undefined);
                        deleteDisclosure.onClose();
                    }}
                />
            )}
        </Dashboard>
    );
}
