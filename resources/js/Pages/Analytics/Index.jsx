import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { Area, AreaChart, CartesianGrid, XAxis, Bar, BarChart } from 'recharts';
import { IconTrendingUp } from '@tabler/icons-react';

export default function Index({ statusCounts, categoryCounts, dateCounts }) {
    const statusData = Object.entries(statusCounts || {}).map(([name, value]) => ({ name, value }));
    const categoryData = Object.entries(categoryCounts || {}).map(([name, tickets]) => ({ name, tickets }));
    const dateData = Object.entries(dateCounts || {}).map(([date, tickets]) => ({ date, tickets }));

    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Analytics</h1>}>
            <Head title="Analytics" />
            <div className="flex flex-col gap-4 py-4 md:gap-6 md:py-6">
                {/* Summary Cards */}
                <div className="grid grid-cols-1 gap-4 px-4 lg:px-6 sm:grid-cols-3">
                    {statusData.map(s => (
                        <div key={s.name} className="rounded-xl border bg-card p-6 shadow-sm">
                            <p className="text-sm text-muted-foreground">{s.name} Tickets</p>
                            <p className="mt-1 text-3xl font-semibold tabular-nums">{s.value}</p>
                            <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">
                                <IconTrendingUp className="size-4" /> Current count
                            </div>
                        </div>
                    ))}
                </div>

                {/* Charts */}
                <div className="grid grid-cols-1 gap-4 px-4 lg:px-6 lg:grid-cols-2">
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <h3 className="mb-4 text-sm font-semibold">Tickets by Category</h3>
                        {categoryData.length > 0 ? (
                            <BarChart width={450} height={250} data={categoryData}>
                                <CartesianGrid vertical={false} strokeDasharray="3 3" />
                                <XAxis dataKey="name" tickLine={false} axisLine={false} fontSize={12} />
                                <Bar dataKey="tickets" fill="hsl(var(--primary))" radius={[4, 4, 0, 0]} />
                            </BarChart>
                        ) : <p className="text-sm text-muted-foreground">No data</p>}
                    </div>
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <h3 className="mb-4 text-sm font-semibold">Ticket Volume Trend</h3>
                        {dateData.length > 0 ? (
                            <AreaChart width={450} height={250} data={dateData}>
                                <defs>
                                    <linearGradient id="fillTickets" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="5%" stopColor="hsl(var(--primary))" stopOpacity={0.8} />
                                        <stop offset="95%" stopColor="hsl(var(--primary))" stopOpacity={0.1} />
                                    </linearGradient>
                                </defs>
                                <CartesianGrid vertical={false} strokeDasharray="3 3" />
                                <XAxis dataKey="date" tickLine={false} axisLine={false} fontSize={12} />
                                <Area type="natural" dataKey="tickets" fill="url(#fillTickets)" stroke="hsl(var(--primary))" />
                            </AreaChart>
                        ) : <p className="text-sm text-muted-foreground">No data</p>}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
