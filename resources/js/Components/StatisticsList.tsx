import React from 'react';
import {Table, Tbody, Td, Th, Thead, Tr} from '@chakra-ui/react';
import {Statistic} from '@/Types/Statistic';

export default function StatisticsList({
    statistics,
}: {
    statistics: Statistic[];
}) {
    return (
        <Table variant="simple">
            <Thead>
                <Tr>
                    <Th>Created</Th>
                    <Th>Who</Th>
                    <Th>Type</Th>
                    <Th>Template</Th>
                </Tr>
            </Thead>
            <Tbody>
                {statistics.map((val: Statistic, idx: number) => {
                    return (
                        <Tr key={idx}>
                            <Td>{new Date(val.created_at).toDateString()}</Td>
                            <Td>{val.application.respondent.name}</Td>
                            <Td>{val.action}</Td>
                            <Td>{val.application.template.name}</Td>
                        </Tr>
                    );
                })}
            </Tbody>
        </Table>
    );
}
