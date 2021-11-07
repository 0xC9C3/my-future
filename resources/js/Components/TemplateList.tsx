import React, {useState} from 'react';
import {
    IconButton,
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
    useDisclosure,
} from '@chakra-ui/react';
import {DeleteIcon, DownloadIcon, ViewIcon} from '@chakra-ui/icons';
import {Template} from '@/Types/Template';
import UpdateTemplateModal from '@/Components/UpdateTemplateModal';
import DeleteTemplateModal from '@/Components/DeleteTemplateModal';

export default function TemplateList({templates}: {templates: Template[]}) {
    const deleteDisclosure = useDisclosure();
    const updateDisclosure = useDisclosure();
    const [template, setTemplate] = useState<Template>();
    return (
        <>
            <Table variant="simple">
                <Thead>
                    <Tr>
                        <Th>Created</Th>
                        <Th>Name</Th>
                        <Th>Download</Th>
                        <Th>PDF</Th>
                        <Th>Update</Th>
                        <Th>Delete</Th>
                        <Th>Preview</Th>
                    </Tr>
                </Thead>
                <Tbody>
                    {templates.map((template, idx) => {
                        return (
                            <Tr key={idx}>
                                <Td>
                                    {new Date(
                                        template.created_at
                                    ).toDateString()}
                                </Td>
                                <Td>{template.name}</Td>
                                <Td>
                                    <IconButton
                                        colorScheme="blue"
                                        icon={<DownloadIcon />}
                                        aria-label="download"
                                        href={route(
                                            'dashboard.templates.download',
                                            {templateId: template.id}
                                        )}
                                        target="_blank"
                                        as="a"
                                        cursor="pointer"
                                    />
                                </Td>
                                <Td>
                                    <IconButton
                                        colorScheme="blue"
                                        icon={<DownloadIcon />}
                                        aria-label="export"
                                        href={route(
                                            'dashboard.templates.export',
                                            {templateId: template.id}
                                        )}
                                        target="_blank"
                                        as="a"
                                        cursor="pointer"
                                    />
                                </Td>
                                <Td>
                                    <IconButton
                                        colorScheme="green"
                                        icon={
                                            <DownloadIcon
                                                style={{
                                                    transform: 'rotate(180deg)',
                                                }}
                                            />
                                        }
                                        aria-label="update"
                                        onClick={() => {
                                            setTemplate(template);
                                            updateDisclosure.onOpen();
                                        }}
                                    />
                                </Td>
                                <Td>
                                    <IconButton
                                        colorScheme="red"
                                        icon={<DeleteIcon />}
                                        aria-label="delete"
                                        onClick={() => {
                                            setTemplate(template);
                                            deleteDisclosure.onOpen();
                                        }}
                                    />
                                </Td>
                                <Td>
                                    <IconButton
                                        icon={<ViewIcon />}
                                        aria-label="preview"
                                        href={
                                            route('portfolio.view') +
                                            '?key=' +
                                            template.key
                                        }
                                        target="_blank"
                                        as="a"
                                        cursor="pointer"
                                    />
                                </Td>
                            </Tr>
                        );
                    })}
                </Tbody>
            </Table>

            {template && (
                <UpdateTemplateModal
                    template={template}
                    isOpen={updateDisclosure.isOpen}
                    onClose={() => {
                        setTemplate(undefined);
                        updateDisclosure.onClose();
                    }}
                />
            )}

            {template && (
                <DeleteTemplateModal
                    template={template}
                    isOpen={deleteDisclosure.isOpen}
                    onClose={() => {
                        setTemplate(undefined);
                        deleteDisclosure.onClose();
                    }}
                />
            )}
        </>
    );
}
