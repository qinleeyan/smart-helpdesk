import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { IconSparkles } from '@tabler/icons-react';

export default function Create({ auth, categories }) {
    const { data, setData, post, processing, errors } = useForm({
        subject: '', category_id: '', priority: 'Normal', description: ''
    });
    const [suggestions, setSuggestions] = useState([]);

    useEffect(() => {
        if (data.subject.length < 3) { setSuggestions([]); return; }
        const t = setTimeout(async () => {
            try {
                const res = await fetch(`/kb/suggest?q=${encodeURIComponent(data.subject)}`);
                setSuggestions(await res.json());
            } catch { }
        }, 500);
        return () => clearTimeout(t);
    }, [data.subject]);

    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Create Ticket</h1>}>
            <Head title="Create Ticket" />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="mx-auto w-full max-w-2xl rounded-xl border bg-card p-6 shadow-sm">
                    <form onSubmit={e => { e.preventDefault(); post(route('tickets.store')); }}>
                        <div className="mb-4 relative">
                            <label className="mb-1.5 block text-sm font-medium">Subject</label>
                            <input type="text" value={data.subject} onChange={e => setData('subject', e.target.value)} required className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" />
                            {errors.subject && <p className="mt-1 text-xs text-destructive">{errors.subject}</p>}
                            {suggestions.length > 0 && (
                                <div className="absolute z-10 mt-1 w-full rounded-lg border bg-popover p-1 shadow-lg">
                                    <div className="flex items-center gap-1.5 px-2 py-1.5 text-xs font-medium text-muted-foreground">
                                        <IconSparkles className="size-3.5" /> AI Suggested Solutions
                                    </div>
                                    {suggestions.map(s => (
                                        <a key={s.id} href={`/kb/${s.slug}`} target="_blank" className="block rounded-md px-2 py-1.5 text-sm hover:bg-accent transition-colors">{s.title}</a>
                                    ))}
                                </div>
                            )}
                        </div>
                        <div className="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <label className="mb-1.5 block text-sm font-medium">Category</label>
                                <select value={data.category_id} onChange={e => setData('category_id', e.target.value)} required className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:ring-1 focus-visible:ring-ring">
                                    <option value="" disabled>Select</option>
                                    {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                                </select>
                            </div>
                            <div>
                                <label className="mb-1.5 block text-sm font-medium">Priority</label>
                                <select value={data.priority} onChange={e => setData('priority', e.target.value)} className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:ring-1 focus-visible:ring-ring">
                                    <option>Low</option><option>Normal</option><option>Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div className="mb-6">
                            <label className="mb-1.5 block text-sm font-medium">Description</label>
                            <textarea rows="5" value={data.description} onChange={e => setData('description', e.target.value)} required className="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring" placeholder="Describe your issue..." />
                        </div>
                        <div className="flex justify-end gap-2">
                            <Link href={route('tickets.index')} className="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm hover:bg-accent transition-colors">Cancel</Link>
                            <button type="submit" disabled={processing} className="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 shadow-sm transition-colors disabled:opacity-50">Submit Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
