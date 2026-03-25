import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import { IconTrendingUp, IconTrendingDown, IconTicket, IconCircleCheckFilled, IconLoader, IconDownload, IconPlus } from '@tabler/icons-react';

export default function Index({ auth, tickets }) {
    const { flash } = usePage().props;

    const statusColor = (s) => {
        if (s === 'Open') return 'text-blue-500';
        if (s === 'In Progress') return 'text-yellow-500';
        return 'text-green-500';
    };

    const statusIcon = (s) => {
        if (s === 'Resolved') return <IconCircleCheckFilled className="fill-green-500 dark:fill-green-400 size-4" />;
        return <IconLoader className="size-4" />;
    };

    const priorityClass = (p) => {
        if (p === 'Urgent') return 'border-red-500/30 text-red-400 bg-red-500/10';
        if (p === 'Normal') return 'border-blue-500/30 text-blue-400 bg-blue-500/10';
        return 'border-muted text-muted-foreground';
    };

    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Tickets</h1>}>
            <Head title="Tickets" />

            <div className="flex flex-col gap-4 py-4 md:gap-6 md:py-6">
                {/* Summary Cards */}
                <div className="grid grid-cols-1 gap-4 px-4 lg:px-6 sm:grid-cols-2 xl:grid-cols-4">
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <p className="text-sm text-muted-foreground">Total Tickets</p>
                        <p className="mt-1 text-3xl font-semibold tabular-nums">{tickets.total || tickets.data?.length || 0}</p>
                        <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">
                            <IconTrendingUp className="size-4 text-green-500" /> Active tracking
                        </div>
                    </div>
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <p className="text-sm text-muted-foreground">Open</p>
                        <p className="mt-1 text-3xl font-semibold tabular-nums text-blue-500">{tickets.data?.filter(t => t.status === 'Open').length || 0}</p>
                        <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">Needs attention</div>
                    </div>
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <p className="text-sm text-muted-foreground">In Progress</p>
                        <p className="mt-1 text-3xl font-semibold tabular-nums text-yellow-500">{tickets.data?.filter(t => t.status === 'In Progress').length || 0}</p>
                        <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">Being worked on</div>
                    </div>
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <p className="text-sm text-muted-foreground">Resolved</p>
                        <p className="mt-1 text-3xl font-semibold tabular-nums text-green-500">{tickets.data?.filter(t => t.status === 'Resolved').length || 0}</p>
                        <div className="mt-2 flex items-center gap-1 text-xs text-green-500"><IconTrendingUp className="size-4" /> Completed</div>
                    </div>
                </div>

                {/* Table */}
                <div className="px-4 lg:px-6">
                    <div className="flex items-center justify-between mb-4">
                        <div />
                        <div className="flex items-center gap-2">
                            {auth.user.role === 'admin' && (
                                <a href="/tickets/export" className="inline-flex items-center gap-2 rounded-md border border-input bg-background px-3 py-2 text-sm font-medium shadow-sm hover:bg-accent hover:text-accent-foreground transition-colors">
                                    <IconDownload className="size-4" /> Export CSV
                                </a>
                            )}
                            <Link href={route('tickets.create')} className="inline-flex items-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors shadow-sm">
                                <IconPlus className="size-4" /> New Ticket
                            </Link>
                        </div>
                    </div>

                    {flash?.success && (
                        <div className="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-500">
                            {flash.success}
                        </div>
                    )}

                    <div className="overflow-hidden rounded-lg border">
                        <table className="w-full text-sm">
                            <thead className="bg-muted">
                                <tr>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">ID</th>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">Subject</th>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">Category</th>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">Priority</th>
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground">SLA Target</th>
                                    {auth.user.role === 'admin' && <th className="px-4 py-3 text-left font-medium text-muted-foreground">User</th>}
                                    <th className="px-4 py-3 text-left font-medium text-muted-foreground"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {(!tickets.data || tickets.data.length === 0) ? (
                                    <tr><td colSpan="7" className="h-24 text-center text-muted-foreground">No tickets found.</td></tr>
                                ) : (
                                    tickets.data.map(ticket => (
                                        <tr key={ticket.id} className="border-t transition-colors hover:bg-muted/50">
                                            <td className="px-4 py-3">#{ticket.id}</td>
                                            <td className="px-4 py-3 font-medium">
                                                <Link href={route('tickets.show', ticket.id)} className="text-foreground hover:underline">
                                                    {ticket.subject}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3">
                                                <span className="inline-flex items-center rounded-md border px-1.5 py-0.5 text-xs text-muted-foreground">{ticket.category?.name || 'N/A'}</span>
                                            </td>
                                            <td className="px-4 py-3">
                                                <span className="inline-flex items-center gap-1 rounded-md border px-1.5 py-0.5 text-xs text-muted-foreground">
                                                    {statusIcon(ticket.status)}
                                                    {ticket.status}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3">
                                                <span className={`inline-flex items-center rounded-md border px-1.5 py-0.5 text-xs ${priorityClass(ticket.priority)}`}>
                                                    {ticket.priority}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3">
                                                {ticket.sla && (
                                                    <span className={`inline-flex items-center rounded-md px-1.5 py-0.5 text-[11px] font-medium ${ticket.sla.status === 'Met' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                        ticket.sla.status === 'Breached' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        }`}>
                                                        {ticket.sla.status === 'Met' ? 'SLA Met' : ticket.sla.message}
                                                    </span>
                                                )}
                                            </td>
                                            {auth.user.role === 'admin' && <td className="px-4 py-3 text-muted-foreground">{ticket.user?.name}</td>}
                                            <td className="px-4 py-3">
                                                <Link href={route('tickets.show', ticket.id)} className="text-sm text-primary hover:underline">View</Link>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
