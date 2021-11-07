import React, {useState} from 'react';
import {
    Accordion,
    Button,
    Flex,
    Heading,
    IconButton,
    Spacer,
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
    useDisclosure,
} from '@chakra-ui/react';
import {AddIcon, DeleteIcon, RepeatIcon} from '@chakra-ui/icons';
import Dashboard from '@/Pages/Dashboard';
import {DefaultProps} from '@/Types/DefaultProps';
import CopyToClipboardButton from '@/Components/CopyToClipboardButton';
import {Applications} from '@/Types/Applications';
import {Application} from '@/Types/Application';
import DeleteApplicationModal from '@/Components/DeleteApplicationModal';
import ResendApplicationModal from '@/Components/ResendApplicationModal';
import ApplicationHistoryModal from '@/Components/ApplicationHistoryModal';
import {Link} from '@inertiajs/inertia-react';

export default function Applications(props: DefaultProps & Applications) {
    const deleteDisclosure = useDisclosure();
    const resendDisclosure = useDisclosure();
    const applicationHistoryDisclosure = useDisclosure();
    const [application, setApplication] = useState<Application>();
    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            <Heading className="py-3" size="md">
                Applications
            </Heading>

            <Accordion className="flex-grow">
                <Table variant="simple">
                    <Thead>
                        <Tr>
                            <Th>Created</Th>
                            <Th>Respondent</Th>
                            <Th>Template</Th>
                            <Th>Delete</Th>
                            <Th>Resend</Th>
                            <Th>Link</Th>
                            <Th>History</Th>
                        </Tr>
                    </Thead>
                    <Tbody>
                        {props.applications.map(
                            (val: Application, idx: number) => {
                                return (
                                    <Tr key={idx}>
                                        <Td>
                                            {new Date(
                                                val.created_at
                                            ).toDateString()}
                                        </Td>
                                        <Td>{val.respondent.name}</Td>
                                        <Td>{val.template.name}</Td>
                                        <Td>
                                            <IconButton
                                                onClick={() => {
                                                    setApplication(val);
                                                    deleteDisclosure.onOpen();
                                                }}
                                                colorScheme="red"
                                                icon={<DeleteIcon />}
                                                aria-label="delete"
                                            />
                                        </Td>
                                        <Td>
                                            <IconButton
                                                colorScheme="green"
                                                icon={<RepeatIcon />}
                                                aria-label="resend"
                                                onClick={() => {
                                                    setApplication(val);
                                                    resendDisclosure.onOpen();
                                                }}
                                            />
                                        </Td>
                                        <Td>
                                            <CopyToClipboardButton
                                                value={
                                                    route('portfolio.view') +
                                                    '?key=' +
                                                    val.key
                                                }
                                            />
                                        </Td>
                                        <Td>
                                            <IconButton
                                                variant="ghost"
                                                icon={<AddIcon />}
                                                aria-label="history"
                                                onClick={() => {
                                                    setApplication(val);
                                                    applicationHistoryDisclosure.onOpen();
                                                }}
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
                <Link href={route('dashboard.apply')} as="button">
                    <Button colorScheme="green" as="div">
                        Add
                    </Button>
                </Link>
            </Flex>

            {application && (
                <DeleteApplicationModal
                    application={application}
                    isOpen={deleteDisclosure.isOpen}
                    onClose={() => {
                        setApplication(undefined);
                        deleteDisclosure.onClose();
                    }}
                />
            )}

            {application && (
                <ResendApplicationModal
                    application={application}
                    isOpen={resendDisclosure.isOpen}
                    onClose={() => {
                        setApplication(undefined);
                        resendDisclosure.onClose();
                    }}
                />
            )}

            {application && (
                <ApplicationHistoryModal
                    application={application}
                    isOpen={applicationHistoryDisclosure.isOpen}
                    onClose={() => {
                        setApplication(undefined);
                        applicationHistoryDisclosure.onClose();
                    }}
                />
            )}
        </Dashboard>
    );
}
