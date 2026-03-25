import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create({ categories }) {
    const { data, setData, post, processing, errors } = useForm({ title: '', category_id: '', content: '', keywords: '' });
    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Create Article</h1>}>
            <Head title="Create Article" />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="mx-auto w-full max-w-2xl rounded-xl border bg-card p-6 shadow-sm">
                    <form onSubmit={e => { e.preventDefault(); post(route('kb.admin.store')); }}>
                        <div className="mb-4"><label className="mb-1.5 block text-sm font-medium">Title</label>
                            <input type="text" value={data.title} onChange={e => setData('title', e.target.value)} required className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:ring-1 focus-visible:ring-ring" />
                            {errors.title && <p className="mt-1 text-xs text-destructive">{errors.title}</p>}
                        </div>
                        <div className="mb-4"><label className="mb-1.5 block text-sm font-medium">Category</label>
                            <select value={data.category_id} onChange={e => setData('category_id', e.target.value)} required className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="" disabled>Select</option>
                                {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                            </select>
                        </div>
                        <div className="mb-4"><label className="mb-1.5 block text-sm font-medium">Content</label>
                            <textarea rows="8" value={data.content} onChange={e => setData('content', e.target.value)} required className="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm focus-visible:ring-1 focus-visible:ring-ring" />
                        </div>
                        <div className="mb-6"><label className="mb-1.5 block text-sm font-medium">Keywords</label>
                            <input type="text" value={data.keywords} onChange={e => setData('keywords', e.target.value)} className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm" placeholder="printer, network, vpn" />
                            <p className="mt-1 text-xs text-muted-foreground">Comma-separated, used for AI auto-suggest.</p>
                        </div>
                        <div className="flex justify-end gap-2">
                            <Link href={route('kb.admin.index')} className="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm shadow-sm hover:bg-accent">Cancel</Link>
                            <button type="submit" disabled={processing} className="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 shadow-sm disabled:opacity-50">Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
