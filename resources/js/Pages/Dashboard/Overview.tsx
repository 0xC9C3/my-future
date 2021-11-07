import React from 'react';
import {
    Box,
    Heading,
    Stat,
    StatGroup,
    StatLabel,
    StatNumber,
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
} from '@chakra-ui/react';
import Dashboard from '@/Pages/Dashboard';
import {DefaultProps} from '@/Types/DefaultProps';
import {Statistics} from '@/Types/Statistics';
import {Statistic} from '@/Types/Statistic';
import StatisticsList from '@/Components/StatisticsList';

export default function Overview(props: DefaultProps & Statistics) {
    return (
        <Dashboard auth={props.auth} errors={props.errors}>
            <Heading className="pb-6">Welcome!</Heading>

            <Box className="py-6">
                <StatGroup>
                    <Stat>
                        <StatLabel>Sent</StatLabel>
                        <StatNumber>{props.statistics.sent}</StatNumber>
                    </Stat>

                    <Stat>
                        <StatLabel>Viewed</StatLabel>
                        <StatNumber>{props.statistics.viewed}</StatNumber>
                    </Stat>

                    <Stat>
                        <StatLabel>Clicked</StatLabel>
                        <StatNumber>{props.statistics.clicked}</StatNumber>
                    </Stat>
                </StatGroup>
            </Box>

            <Box>
                <Heading className="py-3" size="md">
                    Events
                </Heading>

                <StatisticsList statistics={props.statistics.list} />
            </Box>
        </Dashboard>
    );
}
