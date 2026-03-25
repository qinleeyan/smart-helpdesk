import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { IconCircleCheckFilled, IconLoader, IconArrowLeft } from '@tabler/icons-react';

export default function Show({ auth, ticket }) {
    const { flash } = usePage().props;
    const { data, setData, put, processing } = useForm({ status: ticket.status, priority: ticket.priority });
    const submit = (e) => { e.preventDefault(); put(route('tickets.update', ticket.id)); };

    return (
        <AuthenticatedLayout header={
            <div className="flex items-center gap-2">
                <Link href={route('tickets.index')} className="text-muted-foreground hover:text-foreground"><IconArrowLeft className="size-4" /></Link>
                <h1 className="text-base font-medium">Ticket #{ticket.id}</h1>
            </div>
        }>
            <Head title={`Ticket #${ticket.id}`} />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    {/* Main */}
                    <div className="lg:col-span-2 space-y-6">
                        <div className="rounded-xl border bg-card p-6 shadow-sm">
                            <h2 className="text-xl font-semibold">{ticket.subject}</h2>
                            <div className="mt-2 flex flex-wrap gap-3 text-sm text-muted-foreground border-b pb-4 mb-4">
                                <span>By: <strong className="text-foreground">{ticket.user?.name}</strong></span>
                                <span>Category: <strong className="text-foreground">{ticket.category?.name || 'N/A'}</strong></span>
                                <span>Created: <strong className="text-foreground">{new Date(ticket.created_at).toLocaleDateString()}</strong></span>
                            </div>
                            <p className="whitespace-pre-wrap text-sm text-muted-foreground leading-relaxed">{ticket.description}</p>
                        </div>
                        <div className="rounded-xl border bg-card p-6 shadow-sm">
                            <h3 className="mb-4 text-sm font-semibold">Activity Log</h3>
                            <div className="space-y-3">
                                {ticket.activity_logs?.map(log => (
                                    <div key={log.id} className="flex gap-3">
                                        <div className="flex size-7 shrink-0 items-center justify-center rounded-full bg-muted text-xs font-medium">{log.user?.name?.charAt(0)}</div>
                                        <div className="flex-1 rounded-lg border bg-muted/50 p-3 text-sm">
                                            <div className="flex justify-between"><strong>{log.user?.name}</strong><span className="text-xs text-muted-foreground">{new Date(log.created_at).toLocaleString()}</span></div>
                                            <p className="mt-1 text-muted-foreground">{log.details}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                    {/* Sidebar */}
                    <div className="space-y-6">
                        <div className="rounded-xl border bg-card p-6 shadow-sm">
                            <h3 className="mb-4 text-sm font-semibold">Status & Priority</h3>
                            {ticket.sla && (
                                <div className="mb-4 flex items-center justify-between rounded-lg border p-3">
                                    <span className="text-xs font-medium text-muted-foreground uppercase tracking-wider">SLA Target</span>
                                    <span className={`inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ${ticket.sla.status === 'Met' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                        ticket.sla.status === 'Breached' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 animate-pulse' :
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                        }`}>
                                        {ticket.sla.status === 'Met' ? 'Met ✓' : ticket.sla.message}
                                    </span>
                                </div>
                            )}
                            {flash?.success && <div className="mb-3 rounded-md border border-green-500/30 bg-green-500/10 px-3 py-2 text-xs text-green-500">{flash.success}</div>}
                            {auth.user.role === 'admin' ? (
                                <form onSubmit={submit} className="space-y-4">
                                    <div><label className="mb-1.5 block text-xs font-medium text-muted-foreground">Status</label>
                                        <select value={data.status} onChange={e => setData('status', e.target.value)} className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                            <option>Open</option><option>In Progress</option><option>Resolved</option>
                                        </select>
                                    </div>
                                    <div><label className="mb-1.5 block text-xs font-medium text-muted-foreground">Priority</label>
                                        <select value={data.priority} onChange={e => setData('priority', e.target.value)} className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                            <option>Low</option><option>Normal</option><option>Urgent</option>
                                        </select>
                                    </div>
                                    <button type="submit" disabled={processing} className="w-full rounded-md bg-primary py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 shadow-sm disabled:opacity-50">Save Changes</button>
                                </form>
                            ) : (
                                <div className="space-y-3 text-sm">
                                    <div><span className="block text-xs text-muted-foreground uppercase tracking-wider mb-1">Status</span>
                                        <span className="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs">{ticket.status === 'Resolved' ? <IconCircleCheckFilled className="size-3.5 fill-green-500" /> : <IconLoader className="size-3.5" />}{ticket.status}</span>
                                    </div>
                                    <div><span className="block text-xs text-muted-foreground uppercase tracking-wider mb-1">Priority</span>
                                        <span className="inline-flex rounded-md border px-2 py-1 text-xs">{ticket.priority}</span>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
