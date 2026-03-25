import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { IconTrendingUp, IconTrendingDown, IconTicket, IconCircleCheckFilled, IconLoader, IconBook2, IconAlertTriangle } from '@tabler/icons-react';
import { useState } from 'react';
import { Area, AreaChart, CartesianGrid, XAxis, Bar, BarChart, ResponsiveContainer, Tooltip } from 'recharts';

function StatCard({ title, value, description, trend, trendUp, color }) {
    return (
        <div className="rounded-xl border bg-gradient-to-t from-primary/5 to-card p-6 shadow-sm">
            <div className="flex items-center justify-between">
                <p className="text-sm text-muted-foreground">{title}</p>
                {trend && (
                    <span className={`inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-medium ${trendUp ? 'text-green-600 border-green-200 dark:text-green-400 dark:border-green-800' : 'text-red-500 border-red-200 dark:border-red-800'}`}>
                        {trendUp ? <IconTrendingUp className="size-3" /> : <IconTrendingDown className="size-3" />}
                        {trend}
                    </span>
                )}
            </div>
            <p className={`mt-2 text-3xl font-semibold tabular-nums ${color || ''}`}>{value}</p>
            <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">
                {description}
            </div>
        </div>
    );
}

export default function Dashboard({ auth, stats, categoryCounts, dailyTrend, recentTickets }) {
    const [timeRange, setTimeRange] = useState('14d');

    const filteredTrend = timeRange === '7d' ? dailyTrend.slice(-7) : dailyTrend;

    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Dashboard</h1>}>
            <Head title="Dashboard" />

            <div className="flex flex-col gap-4 py-4 md:gap-6 md:py-6">
                {/* Section Cards - matching v0 reference */}
                <div className="grid grid-cols-1 gap-4 px-4 lg:px-6 sm:grid-cols-2 xl:grid-cols-4">
                    <StatCard
                        title="Total Tickets"
                        value={stats.totalTickets}
                        trend="+100%"
                        trendUp={true}
                        description={
                            <span className="flex items-center gap-1"><IconTrendingUp className="size-3.5 text-green-500" /> All time tracking</span>
                        }
                    />
                    <StatCard
                        title="Open Tickets"
                        value={stats.openTickets}
                        color="text-blue-500"
                        trend={stats.openTickets > 3 ? 'Needs attention' : 'On track'}
                        trendUp={stats.openTickets <= 3}
                        description="Awaiting resolution"
                    />
                    <StatCard
                        title="Resolution Rate"
                        value={`${stats.resolveRate}%`}
                        color="text-green-500"
                        trend={`${stats.resolvedTickets} resolved`}
                        trendUp={stats.resolveRate > 30}
                        description={
                            <span className="flex items-center gap-1"><IconTrendingUp className="size-3.5 text-green-500" /> Strong performance</span>
                        }
                    />
                    <StatCard
                        title="KB Articles"
                        value={stats.totalArticles}
                        color="text-purple-500"
                        trend="Active"
                        trendUp={true}
                        description={
                            <span className="flex items-center gap-1"><IconBook2 className="size-3.5" /> Knowledge coverage</span>
                        }
                    />
                </div>

                {/* Charts Row - matching v0 reference with interactive chart */}
                <div className="grid grid-cols-1 gap-4 px-4 lg:px-6 lg:grid-cols-7">
                    {/* Area Chart - takes 4/7 */}
                    <div className="lg:col-span-4 rounded-xl border bg-card shadow-sm">
                        <div className="flex items-center justify-between p-6 pb-2">
                            <div>
                                <h3 className="text-sm font-semibold">Ticket Volume</h3>
                                <p className="text-xs text-muted-foreground">Daily submissions over time</p>
                            </div>
                            <div className="flex rounded-lg border p-0.5">
                                <button onClick={() => setTimeRange('14d')} className={`rounded-md px-3 py-1 text-xs font-medium transition-colors ${timeRange === '14d' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'}`}>14 days</button>
                                <button onClick={() => setTimeRange('7d')} className={`rounded-md px-3 py-1 text-xs font-medium transition-colors ${timeRange === '7d' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'}`}>7 days</button>
                            </div>
                        </div>
                        <div className="p-6 pt-2">
                            <ResponsiveContainer width="100%" height={280}>
                                <AreaChart data={filteredTrend}>
                                    <defs>
                                        <linearGradient id="gradient" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="5%" stopColor="hsl(220, 70%, 50%)" stopOpacity={0.3} />
                                            <stop offset="95%" stopColor="hsl(220, 70%, 50%)" stopOpacity={0} />
                                        </linearGradient>
                                    </defs>
                                    <CartesianGrid vertical={false} strokeDasharray="3 3" stroke="hsl(var(--border))" />
                                    <XAxis dataKey="date" tickLine={false} axisLine={false} fontSize={11} tick={{ fill: 'hsl(var(--muted-foreground))' }} />
                                    <Tooltip
                                        contentStyle={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))', borderRadius: '8px', fontSize: '12px' }}
                                        labelStyle={{ color: 'hsl(var(--foreground))' }}
                                    />
                                    <Area type="monotone" dataKey="tickets" stroke="hsl(220, 70%, 50%)" fill="url(#gradient)" strokeWidth={2} />
                                </AreaChart>
                            </ResponsiveContainer>
                        </div>
                    </div>

                    {/* Bar Chart - takes 3/7 */}
                    <div className="lg:col-span-3 rounded-xl border bg-card shadow-sm">
                        <div className="p-6 pb-2">
                            <h3 className="text-sm font-semibold">Tickets by Category</h3>
                            <p className="text-xs text-muted-foreground">Distribution across departments</p>
                        </div>
                        <div className="p-6 pt-2">
                            <ResponsiveContainer width="100%" height={280}>
                                <BarChart data={categoryCounts} layout="vertical">
                                    <CartesianGrid horizontal={false} strokeDasharray="3 3" stroke="hsl(var(--border))" />
                                    <XAxis type="number" tickLine={false} axisLine={false} fontSize={11} tick={{ fill: 'hsl(var(--muted-foreground))' }} />
                                    <Tooltip
                                        contentStyle={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))', borderRadius: '8px', fontSize: '12px' }}
                                    />
                                    <Bar dataKey="tickets" fill="hsl(220, 70%, 50%)" radius={[0, 4, 4, 0]} barSize={24} />
                                </BarChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>

                {/* Recent Tickets Table */}
                <div className="px-4 lg:px-6">
                    <div className="rounded-xl border bg-card shadow-sm">
                        <div className="flex items-center justify-between p-6 pb-4">
                            <div>
                                <h3 className="text-sm font-semibold">Recent Tickets</h3>
                                <p className="text-xs text-muted-foreground">Latest support requests</p>
                            </div>
                            <Link href={route('tickets.index')} className="text-xs font-medium text-primary hover:underline">View all →</Link>
                        </div>
                        <div className="overflow-hidden border-t">
                            <table className="w-full text-sm">
                                <thead className="bg-muted/50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Subject</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Priority</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">SLA Target</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Category</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Submitted by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {recentTickets?.map(ticket => (
                                        <tr key={ticket.id} className="border-t transition-colors hover:bg-muted/50">
                                            <td className="px-6 py-4">
                                                <Link href={route('tickets.show', ticket.id)} className="font-medium hover:underline">{ticket.subject}</Link>
                                            </td>
                                            <td className="px-6 py-4">
                                                <span className={`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-medium ${ticket.status === 'Resolved' ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950 dark:text-green-400' :
                                                    ticket.status === 'In Progress' ? 'border-yellow-200 bg-yellow-50 text-yellow-700 dark:border-yellow-800 dark:bg-yellow-950 dark:text-yellow-400' :
                                                        'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-400'
                                                    }`}>
                                                    {ticket.status === 'Resolved' ? <IconCircleCheckFilled className="size-3" /> : <IconLoader className="size-3" />}
                                                    {ticket.status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4">
                                                <span className={`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium ${ticket.priority === 'Urgent' ? 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-400' :
                                                    ticket.priority === 'Normal' ? 'border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300' :
                                                        'border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-500'
                                                    }`}>
                                                    {ticket.priority === 'Urgent' && <IconAlertTriangle className="size-3 mr-1" />}
                                                    {ticket.priority}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4">
                                                {ticket.sla && (
                                                    <span className={`inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-medium ${ticket.sla.status === 'Met' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                        ticket.sla.status === 'Breached' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        }`}>
                                                        {ticket.sla.status === 'Met' ? 'SLA Met' : ticket.sla.message}
                                                    </span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4">
                                                <span className="inline-flex items-center rounded-md border px-2 py-0.5 text-xs text-muted-foreground">{ticket.category?.name || 'N/A'}</span>
                                            </td>
                                            <td className="px-6 py-4 text-muted-foreground">{ticket.user?.name}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
